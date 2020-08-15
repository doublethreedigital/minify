var hello = 'Hello'
let hiya = 'Hiya'
const world = 'World'

function doStuff() {
    var finalText = hello + ' ' + world
}

document.getElementById('hiya').innerText = hiya

window.onload = doStuff()
