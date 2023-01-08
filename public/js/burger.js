let burger = document.getElementById('burger-btn')
let menu = document.getElementById('menu')

// Burger button function (show menu on click)
burger.addEventListener('click', () => {
    if (menu.classList.contains('z-[-1]') && menu.classList.contains('opacity-0')) {
        menu.classList.remove('z-[-1]', 'opacity-0', 'scale-y-0')
        menu.classList.add('z-1', 'opacity-1', 'scale-y-1')
    } else {
        menu.classList.remove('z-1', 'opacity-1', 'scale-y-1')
        menu.classList.add('z-[-1]', 'opacity-0', 'scale-y-0')
    }
})

// Hide menu on click on link
menu.addEventListener('click', () => {
    menu.classList.remove('z-1', 'opacity-1', 'scale-y-1')
    menu.classList.add('z-[-1]', 'opacity-0', 'scale-y-0')
})