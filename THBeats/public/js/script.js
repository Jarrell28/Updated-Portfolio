//fixed nav
const handleNav = debounce(function() {
    let windowScroll = window.scrollY;
    let bodyTop = document.body.getBoundingClientRect().top;
    let beatsScrollTop = document.querySelector("#music").getBoundingClientRect().top -20;
    if (windowScroll >= beatsScrollTop - bodyTop) {
        document.querySelector("nav").classList.add("fixed-nav");
    } else {
        document.querySelector("nav").classList.remove("fixed-nav");
    }
}, 150);
    
window.addEventListener("scroll", handleNav);


//mobile nav 
const navButton = document.querySelector(".nav-container button");
const navList = document.querySelector(".nav-container ul");
navButton.addEventListener("click", () => { navList.classList.toggle("active-nav")});

//debounce
function debounce(func, wait, immediate) {
    var timeout;
    return function () {
        var context = this, args = arguments;
        var later = function () {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
};


function addToStorage(storage, id) {
    $.ajax({
        url: "/THBeats/public/cart/add/" + storage + "/" + id,
        type: "GET",
        success: function(data) {
            $("[data-cartId=" + id + "]").removeClass("fa-shopping-cart").addClass("fa-check").removeAttr('onclick');
            console.log(data);
        }
    })
}

function deleteFromCart(id) {
    $.ajax({
        url: "/home/deleteFromCart/" + id,
        type: "POST",
        data: {removeItem: id},
        success: function(data) {
            $(`li i[data-id='${id}']`).parent().remove();
            $(`td i[data-cartid=${id}]`).removeClass('fa-check').addClass('fa-shopping-cart').attr('onclick', `addToCart(${id})`);
            const results = JSON.parse(data);
            $(".price").html(results.total);
        }
    })
}

// $("#paypal").on("click", function(e) {
//     e.preventDefault();
//     $.ajax({
//         url: "checkout.php",
//         type: "POST",
//         data: {checkout: ""},
//         success: function(data){
//             const checkoutData = JSON.parse(data);
//             const checkoutHtml = checkoutData.map(data => {
//                 return `
//                     <input type="hidden" name="item_name_${data.item_name}" value="${data.title}">
//                     <input type="hidden" name="item_number_${data.item_number}" value="${data.id}" >
//                     <input type="hidden" name="amount_${data.amount}" value="${data.price}">
//                     <input type="hidden" name="quantity_${data.quantity}" value="${data.value}">
//                 `;
//             }).join("");
//             $(".checkout form").append(checkoutHtml);
//             $("#paypalButton").trigger("click");
//         }
//     });
// });
//
// $("#email-submit").on("click", function() {
//     if($(".checkout input[name='email']").val() === ""){
//         alert("failed");
//         return;
//     }
//     const email = $(".checkout input[name='email']").val();
//     $.ajax({
//         url: "/home/emailLogin/",
//         type: "POST",
//         data: {email},
//         success: function() {
//             location.reload();
//         }
//     })
// });