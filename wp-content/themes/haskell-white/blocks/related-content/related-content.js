var swiper = new Swiper(".related-content-section", {
      navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
      },
      autoplay: {
          delay: 5500
      },
      spaceBetween: 30,
      breakpoints: {
    1024: {
          slidesPerView: 3,
          spaceBetween: 35,
        },
      768: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
      320: {
          slidesPerView: 1,
          spaceBetween: 20,
        }
    },
});