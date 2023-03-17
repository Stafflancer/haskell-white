var swiper = new Swiper(".featured-posts-slider", {
    scrollbar: {
        el: ".swiper-scrollbar",
        draggable: true,
        hide: true,
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    autoplay: {
        delay: 5500
    },
    spaceBetween: 30,
    watchSlidesProgress: true,
	breakpoints: {
	1024: {
        slidesPerView: 1,
        spaceBetween: 20,
      },
	  768: {
        slidesPerView: 1,
        spaceBetween: 20,
      },
	  320: {
        slidesPerView: 1,
        spaceBetween: 20,
      }
	},
    slidesPerView: 3,
});