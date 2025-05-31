let list = document.querySelector('.pic-carousel .pics-list');
let items = document.querySelectorAll('.pic-carousel .pics-list .pics');
let dots = document.querySelectorAll('.pic-carousel .dots li');
let prev = document.getElementById('previous');
let next = document.getElementById('next');

let active = 0;
let lengthItems = items.length - 1;

function initSlider() {
    items.forEach((item, index) => {
        item.style.left = `${index * 100}%`;
    });
}

window.addEventListener('load', initSlider);

next.onclick = function() {
    if (active + 1 > lengthItems) {
        active = 0;
    } else {
        active = active + 1;
    }
    reloadSlider();
}

prev.onclick = function() {
    if (active - 1 < 0) {
        active = lengthItems;
    } else {
        active = active - 1;
    }
    reloadSlider();
}

let refreshSlider = setInterval(() => { next.click() }, 2500);

function reloadSlider() {
    let checkLeft = items[active].offsetLeft;
    list.style.left = -checkLeft + 'px';

    let lastActiveDot = document.querySelector('.pic-carousel .dots li.acting');
    lastActiveDot.classList.remove('acting');
    dots[active].classList.add('acting');

    clearInterval(refreshSlider);
    refreshSlider = setInterval(() => { next.click() }, 5000);
}

dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
        active = index;
        reloadSlider();
    });
});

list.addEventListener('mouseenter', () => {
    clearInterval(refreshSlider);
});

list.addEventListener('mouseleave', () => {
    refreshSlider = setInterval(() => { next.click() }, 5000);
});
