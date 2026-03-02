#!/usr/bin/env node

const path = require('path');
const { spawn } = require('child_process');
const fs = require('fs-extra');
const chokidar = require('chokidar');
const browserSync = require('browser-sync');

const repoRoot = path.resolve(__dirname, '..');
const ansi = {
	reset: '\x1b[0m',
	bold: '\x1b[1m',
	dim: '\x1b[2m',
	gray: '\x1b[90m',
	red: '\x1b[31m',
	green: '\x1b[32m',
	yellow: '\x1b[33m',
	blue: '\x1b[34m',
	magenta: '\x1b[35m',
	cyan: '\x1b[36m',
};

function getArgValue(flag) {
	const index = process.argv.indexOf(flag);

	if (index === -1 || index === process.argv.length - 1) {
		return '';
	}

	return process.argv[index + 1];
}

function hasFlag(flag) {
	return process.argv.includes(flag);
}

async function loadConfigFile(configPath, isExplicit) {
	if (!configPath) {
		return {};
	}

	const resolvedPath = path.resolve(configPath);
	const exists = await fs.pathExists(resolvedPath);

	if (!exists) {
		if (isExplicit) {
			throw new Error(`Config file not found: ${resolvedPath}`);
		}

		return {};
	}

	const raw = await fs.readFile(resolvedPath, 'utf8');
	const config = JSON.parse(raw);

	return {
		...config,
		__configPath: resolvedPath,
	};
}

function resolveRequiredPath(inputPath, label) {
	if (!inputPath) {
		throw new Error(`${label} is required.`);
	}

	return path.resolve(inputPath);
}

function now() {
	return new Date().toLocaleTimeString('ru-RU', { hour12: false });
}

function paint(color, text) {
	return `${ansi[color] || ''}${text}${ansi.reset}`;
}

function tone(label) {
	const palette = {
		boot: 'magenta',
		init: 'cyan',
		sync: 'green',
		watch: 'blue',
		reload: 'yellow',
		error: 'red',
		stop: 'gray',
	};

	return palette[label] || 'cyan';
}

function log(label, icon, message) {
	const time = paint('gray', `[${now()}]`);
	const tag = paint(tone(label), `${icon} ${label.toUpperCase()}`);
	console.log(`${time} ${tag} ${message}`);
}

function logBlock(lines) {
	for (const line of lines) {
		console.log(line);
	}
}

function toRepoRelativePath(targetPath) {
	return path.relative(repoRoot, targetPath).split(path.sep).join('/');
}

async function ensureDirectory(directoryPath, label) {
	const exists = await fs.pathExists(directoryPath);

	if (!exists) {
		throw new Error(`${label} not found: ${directoryPath}`);
	}
}

async function assertNotSymlink(targetPath, label) {
	const exists = await fs.pathExists(targetPath);

	if (!exists) {
		return;
	}

	const stat = await fs.lstat(targetPath);

	if (stat.isSymbolicLink()) {
		throw new Error(`${label} is a symlink/junction and must be replaced with a normal directory: ${targetPath}`);
	}
}

function shouldIgnore(sourcePath) {
	const normalizedPath = sourcePath.split(path.sep).join('/');
	const ignoredSegments = [
		'/.git/',
		'/.github/',
		'/node_modules/',
		'/vendor/',
		'/dist/',
	];

	if (ignoredSegments.some((segment) => normalizedPath.includes(segment))) {
		return true;
	}

	return normalizedPath.endsWith('/.DS_Store') || normalizedPath.endsWith('/Thumbs.db');
}

async function copyEntry(sourcePath, destinationPath) {
	await fs.ensureDir(path.dirname(destinationPath));
	await fs.copy(sourcePath, destinationPath, {
		overwrite: true,
		errorOnExist: false,
		filter: (currentPath) => !shouldIgnore(currentPath),
	});
}

async function removeEntry(destinationPath) {
	await fs.remove(destinationPath);
}

function createChangeHandler(options) {
	const queue = new Map();
	let timer = null;
	let running = false;

	async function flushQueue() {
		if (running || queue.size === 0) {
			return;
		}

		running = true;
		const entries = Array.from(queue.entries());
		queue.clear();
		let hasChanges = false;

		for (const [changedPath, eventName] of entries) {
			const destinationPath = options.mapDestination(changedPath);

			try {
				if (eventName === 'add' || eventName === 'change') {
					await copyEntry(changedPath, destinationPath);
					hasChanges = true;
					log('sync', '🧩', `${options.scope} ${eventName} -> ${toRepoRelativePath(changedPath)}`);
				}

				if (eventName === 'addDir') {
					await fs.ensureDir(destinationPath);
					hasChanges = true;
					log('sync', '📁', `${options.scope} addDir -> ${toRepoRelativePath(changedPath)}`);
				}

				if (eventName === 'unlink' || eventName === 'unlinkDir') {
					await removeEntry(destinationPath);
					hasChanges = true;
					log('sync', '🗑️', `${options.scope} ${eventName} -> ${toRepoRelativePath(changedPath)}`);
				}
			} catch (error) {
				log('error', '💥', `sync failed for ${toRepoRelativePath(changedPath)}`);
				console.error(paint('red', error.message));
			}
		}

		if (hasChanges) {
			options.browser.reload();
			log('reload', '🔁', `BrowserSync refreshed after ${options.scope} changes`);
		}

		running = false;

		if (queue.size > 0) {
			await flushQueue();
		}
	}

	return function enqueueChange(eventName, changedPath) {
		if (shouldIgnore(changedPath)) {
			return;
		}

		queue.set(changedPath, eventName);

		if (timer) {
			clearTimeout(timer);
		}

		timer = setTimeout(() => {
			timer = null;
			void flushQueue();
		}, 120);
	};
}

async function syncDirectory(sourcePath, destinationPath) {
	if (process.platform === 'win32') {
		await syncDirectoryWithRobocopy(sourcePath, destinationPath);
		return;
	}

	await fs.ensureDir(destinationPath);
	await fs.copy(sourcePath, destinationPath, {
		overwrite: true,
		errorOnExist: false,
		filter: (currentPath) => !shouldIgnore(currentPath),
	});
}

async function syncDirectoryWithRobocopy(sourcePath, destinationPath) {
	await fs.ensureDir(destinationPath);

	await new Promise((resolve, reject) => {
		const args = [
			sourcePath,
			destinationPath,
			'/MIR',
			'/FFT',
			'/R:2',
			'/W:2',
			'/NFL',
			'/NDL',
			'/NJH',
			'/NJS',
			'/NP',
			'/XD',
			'.git',
			'.github',
			'node_modules',
		];
		const child = spawn('robocopy', args, {
			stdio: 'ignore',
			shell: true,
		});

		child.on('error', (error) => {
			reject(error);
		});

		child.on('close', (code) => {
			if (code <= 7) {
				resolve();
				return;
			}

			reject(new Error(`robocopy failed with exit code ${code}`));
		});
	});
}

async function main() {
	const explicitConfigPath = getArgValue('--config');
	const fallbackConfigPath = path.join(repoRoot, '.studio.local.json');
	const loadedConfig = await loadConfigFile(
		explicitConfigPath || fallbackConfigPath,
		Boolean(explicitConfigPath)
	);

	const studioSitePath = resolveRequiredPath(
		getArgValue('--studioSitePath') || process.env.STUDIO_SITE_PATH || loadedConfig.studioSitePath,
		'Studio site path'
	);
	const studioUrl =
		getArgValue('--studioUrl') || process.env.STUDIO_URL || loadedConfig.studioUrl || 'http://localhost:8881';
	const themeSlug = getArgValue('--themeSlug') || process.env.THEME_SLUG || loadedConfig.themeSlug || 'a4-remont';
	const syncPlugins =
		hasFlag('--syncPlugins') ||
		process.env.SYNC_PLUGINS === 'true' ||
		Boolean(loadedConfig.syncPlugins);
	const onceMode =
		hasFlag('--once') ||
		process.env.STUDIO_SYNC_ONCE === 'true' ||
		Boolean(loadedConfig.once);
	const browserPort = Number(
		getArgValue('--browserPort') || process.env.BROWSER_SYNC_PORT || loadedConfig.browserPort || 3000
	);

	const sourceThemePath = path.join(repoRoot, 'wp-content', 'themes', themeSlug);
	const destinationThemePath = path.join(studioSitePath, 'wp-content', 'themes', themeSlug);
	const sourcePluginsPath = path.join(repoRoot, 'wp-content', 'plugins');
	const destinationPluginsPath = path.join(studioSitePath, 'wp-content', 'plugins');

	await ensureDirectory(studioSitePath, 'Studio site path');
	await ensureDirectory(sourceThemePath, 'Theme source directory');
	await ensureDirectory(path.join(studioSitePath, 'wp-content', 'themes'), 'Studio themes directory');
	await ensureDirectory(path.join(studioSitePath, 'wp-content', 'plugins'), 'Studio plugins directory');
	await assertNotSymlink(destinationThemePath, 'Theme destination');

	logBlock([
		'',
		paint('magenta', `${ansi.bold}✨ A4 Remont Studio Live${ansi.reset}`),
		paint('gray', '────────────────────────────────────────────'),
		`${paint('cyan', '🎯 Theme:')} ${themeSlug}`,
		`${paint('cyan', '🧭 Config:')} ${loadedConfig.__configPath || 'cli/env only'}`,
		`${paint('cyan', '📍 Studio site:')} ${studioSitePath}`,
		`${paint('cyan', '🌐 Studio URL:')} ${studioUrl}`,
		`${paint('cyan', '🧪 BrowserSync port:')} ${String(browserPort)}`,
		`${paint('cyan', '🧰 Plugin sync:')} ${syncPlugins ? 'on' : 'off'}`,
		`${paint('cyan', '⚙️ Mode:')} ${onceMode ? 'sync-once' : 'live-watch'}`,
		paint('gray', '────────────────────────────────────────────'),
		'',
	]);

	log('init', '🚚', `Initial theme sync -> ${themeSlug}`);
	await syncDirectory(sourceThemePath, destinationThemePath);
	log('sync', '✅', `Theme is synced to ${destinationThemePath}`);

	if (syncPlugins) {
		await ensureDirectory(sourcePluginsPath, 'Project plugins directory');
		const pluginEntries = await fs.readdir(sourcePluginsPath);

		for (const pluginName of pluginEntries) {
			const sourcePluginPath = path.join(sourcePluginsPath, pluginName);
			const pluginStat = await fs.stat(sourcePluginPath);

			if (!pluginStat.isDirectory()) {
				continue;
			}

			const destinationPluginPath = path.join(destinationPluginsPath, pluginName);
			await assertNotSymlink(destinationPluginPath, `Plugin destination '${pluginName}'`);
			await syncDirectory(sourcePluginPath, destinationPluginPath);
			log('sync', '🧩', `Plugin synced -> wp-content/plugins/${pluginName}`);
		}
	}

	if (onceMode) {
		logBlock([
			'',
			paint('green', `${ansi.bold}✅ Sync-only mode complete${ansi.reset}`),
			`${paint('yellow', '👉 Studio URL:')} ${paint('bold', studioUrl)}`,
			'',
		]);
		return;
	}

	const bs = browserSync.create();
	const handleThemeChange = createChangeHandler({
		browser: bs,
		scope: 'theme',
		mapDestination: (changedPath) => path.join(destinationThemePath, path.relative(sourceThemePath, changedPath)),
	});

	const watchers = [
		chokidar.watch(sourceThemePath, {
			ignored: shouldIgnore,
			ignoreInitial: true,
			awaitWriteFinish: {
				stabilityThreshold: 150,
				pollInterval: 50,
			},
		}),
	];

	watchers[0]
		.on('add', (changedPath) => handleThemeChange('add', changedPath))
		.on('change', (changedPath) => handleThemeChange('change', changedPath))
		.on('unlink', (changedPath) => handleThemeChange('unlink', changedPath))
		.on('addDir', (changedPath) => handleThemeChange('addDir', changedPath))
		.on('unlinkDir', (changedPath) => handleThemeChange('unlinkDir', changedPath));

	if (syncPlugins) {
		const handlePluginChange = createChangeHandler({
			browser: bs,
			scope: 'plugin',
			mapDestination: (changedPath) => path.join(destinationPluginsPath, path.relative(sourcePluginsPath, changedPath)),
		});

		const pluginWatcher = chokidar.watch(sourcePluginsPath, {
			ignored: shouldIgnore,
			ignoreInitial: true,
			awaitWriteFinish: {
				stabilityThreshold: 150,
				pollInterval: 50,
			},
		});

		pluginWatcher
			.on('add', (changedPath) => handlePluginChange('add', changedPath))
			.on('change', (changedPath) => handlePluginChange('change', changedPath))
			.on('unlink', (changedPath) => handlePluginChange('unlink', changedPath))
			.on('addDir', (changedPath) => handlePluginChange('addDir', changedPath))
			.on('unlinkDir', (changedPath) => handlePluginChange('unlinkDir', changedPath));

		watchers.push(pluginWatcher);
	}

	bs.init({
		proxy: studioUrl,
		open: false,
		notify: false,
		ghostMode: false,
		ui: false,
		port: browserPort,
	});

	log('watch', '👀', `Watching theme files in ${sourceThemePath}`);

	if (syncPlugins) {
		log('watch', '🧠', `Watching plugin files in ${sourcePluginsPath}`);
	}

	logBlock([
		'',
		paint('green', `${ansi.bold}⚡ Live mode is ready${ansi.reset}`),
		`${paint('yellow', '👉 Open this URL:')} ${paint('bold', `http://localhost:${browserPort}`)}`,
		`${paint('gray', '   Do not use the raw Studio URL while live reload is running.')}`,
		'',
	]);

	const shutdown = async () => {
		log('stop', '🛑', 'Shutting down watcher and BrowserSync');

		for (const watcher of watchers) {
			await watcher.close();
		}

		bs.exit();
		process.exit(0);
	};

	process.on('SIGINT', () => {
		void shutdown();
	});

	process.on('SIGTERM', () => {
		void shutdown();
	});
}

main().catch((error) => {
	log('error', '💀', 'Studio live boot failed');
	console.error(paint('red', error.message));
	process.exit(1);
});
