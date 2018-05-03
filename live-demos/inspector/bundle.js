window.onload = function(){
    theSecond();
};

function theSecond(){
    let daButton = document.querySelector('#daButton');
    alert('loading...');
    daButton.style.fontSize = "60px";
    daButton.innerHTML = "I said click me!"
}