(function(){

const gallery = document.querySelector('.gallery');
const caption = document.querySelector('p.caption');
const breakPx = 800;

if(window.location.hash) {
    try {
        const hash = window.location.hash.replace('#', '');
        const image = gallery.querySelector(`.image[data-hash="${hash}"]`);
        image.scrollIntoView({
            inline: 'center',
            block: 'center'
        });
        gallery.querySelector('.image.focused').classList.remove('focused');
        image.classList.add('focused');
    } catch(ignored) {}
}

// Intercept right click on images
gallery.addEventListener('contextmenu', event => {
    let originalSrc = event.target.src;
    event.target.src = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
    setTimeout(() => event.target.src = originalSrc, 0);
});

// Slide to images when clicked
gallery.querySelectorAll('.image').forEach(image => {
    image.addEventListener('click', () => {
        history.replaceState(undefined, undefined, '#' + image.dataset.hash);
        if(window.innerWidth < breakPx) return;

        image.scrollIntoView({
            behavior: 'smooth',
            inline: 'center',
            block: 'center'
        });

        gallery.querySelector('.image.focused').classList.remove('focused');
        image.classList.add('focused');
        caption.textContent = image.dataset.caption;
    });
});

// Change slide with window keys
window.addEventListener('keydown', event => {
    if(window.innerWidth < breakPx) return;

    if(event.key === 'ArrowRight') {
        const nextImage = gallery.querySelector('.image.focused').nextElementSibling;
        if(nextImage) nextImage.click();
        // else firstImage.click();
        event.preventDefault();
    }

    if(event.key === 'ArrowLeft') {
        const prevImage = gallery.querySelector('.image.focused').previousElementSibling;
        if(prevImage) prevImage.click();
        // else lastImage.click();
        event.preventDefault();
    }

    if(event.key === 'ArrowUp' || event.key === 'ArrowDown') {
        event.preventDefault();
    }
});

})()