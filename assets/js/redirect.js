function countDown() {
    var timer = document.getElementById("timer"); // Timer ID 
    if (count > 0) {
        // count--; 
        timer.innerHTML = "Vous serez redirigé·e dans <strong>" + count + "</strong> secondes."; // Timer Message
        setTimeout("countDown()", 1000);
        //count == 0 ? setTimeout("countDown()", 3800):setTimeout("countDown()", 1000);

    } else {
        window.location.href = redirect;
    }
}

countDown();
if (window.innerWidth > 768) {
    scrollTo(0, 400);
}

createPageTransition();
//setInterval(createPageTransition, 3000);

function createPageTransition() {
    const el = document.createElement('div');
    el.className = 'page-transition';
    const overlay = document.createElement('div');
    overlay.className = 'page-transition__overlay';
    el.appendChild(overlay);
    document.body.appendChild(el);
    setTimeout(() => {
        el.className = 'page-transition page-transition--in';
    }, 100);
}

// écouteur d'évènement sur le defilement de la page
// window.addEventListener('scroll', function() {
//     var scroll = window.pageYOffset;
//     console.log(scroll);
// });