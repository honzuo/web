// Simple banner carousel
let slideIndex = 0;
carousel();
function carousel() {
    let slides = document.getElementsByClassName("banner-slide");
    for (let i = 0; i < slides.length; i++) slides[i].style.display = "none";
    slideIndex++;
    if (slideIndex > slides.length) slideIndex = 1;
    if(slides[slideIndex-1]) slides[slideIndex-1].style.display = "block";
    setTimeout(carousel, 3000);
}

// Add to cart function
function addToCart(productId) {
    // 这里可以实现添加到购物车的功能
    alert("Product added to cart!");
    console.log("Added product ID: " + productId);
}

// Buy now function
function buyNow(productId) {
    // 这里可以实现立即购买的功能
    alert("Proceeding to checkout...");
    console.log("Buy now - Product ID: " + productId);
    // 可以跳转到结账页面
    // window.location.href = "checkout.php?id=" + productId;
}