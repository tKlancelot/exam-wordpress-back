
const initState = () => {
    let allExcerpts = document.querySelectorAll('.book-card__excerpt-frame');
    allExcerpts.forEach((e) => {
        e.style.display = "none";
    })
}



window.addEventListener("DOMContentLoaded", (event) => {
    // console.log("DOM entièrement chargé et analysé");

    // recuperer toutes les books cards 
    let allBookCards = document.querySelectorAll('.book-card__option-frame svg');
    let allClose = document.querySelectorAll('.book-card__excerpt-frame button');

    initState();

    console.log(allBookCards);

    allBookCards.forEach(element => {
        element.addEventListener('click',function(){
            let nextSibling = element.parentElement.nextElementSibling;
            nextSibling.style.display = "flex";
            // toggle = 1;
        })
    });
    allClose.forEach(element => {
        element.addEventListener('click',function(){
            let parent = element.parentElement.parentElement.parentElement;
            parent.style.display = "none";
            // toggle = 1;
        })
    });


});