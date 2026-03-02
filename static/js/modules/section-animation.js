// sections animations
export function initSectionAnimation() {
	const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                console.log('Element is visible:', entry.target);
                entry.target.classList.add('animated');
                observer.unobserve(entry.target);
            }
        });
    }, {
        root: null, 
        rootMargin: '0px', 
        threshold: 0.01
    });
  
    const sections = document.querySelectorAll('section');
    console.log('Sections found:', sections);  
  
    sections.forEach(section => {
        observer.observe(section);
    });
}