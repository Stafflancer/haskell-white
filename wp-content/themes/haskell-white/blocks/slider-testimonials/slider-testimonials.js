if (jQuery(".testimonials-main").hasClass("testimonials-slider")) {
	var swiper = new Swiper(".testimonials-slider", {
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
	});
}

if (jQuery(".testimonials-main").hasClass("center-testimonials")) {
 	var swiper = new Swiper(".center-testimonials", {
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
	    slidesPerView: 1,
	});
}