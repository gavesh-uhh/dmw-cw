let list = document.querySelector('.pic-carousel .pics-list');
let items = document.querySelectorAll('.pic-carousel .pics-list .pics');
let dots = document.querySelectorAll('.pic-carousel .dots li');
let prev = document.getElementById('previous');
let next = document.getElementById('next');


let active = 0;
let lenghtItems = items.length - 1;



next.onclick = function () {
  if (active + 1 > lenghtItems) {
    active = 0;
  } else {
    active = active += 1;
  }
  relaodSlider()
}

prev.onclick = function () {
  if (active - 1 > lenghtItems) {
    active = 0;
  } else {
    active = active - 1;
  }
  relaodSlider()
}

let refreshSlider = setInterval(() => { next.click()}, 5000);

function relaodSlider() {
  let checkleft = items[active].offsetLeft;
  list.style.left = -checkleft + 'px'

  let lastActiveDot = document.querySelector('.pic-carousel .dots li.acting');
  lastActiveDot.classList.remove('acting');
  dots[active].classList.add('acting');

  clearInterval(refreshSlider);
  refreshSlider = setInterval(() => {next.click()}, 4000);
}
