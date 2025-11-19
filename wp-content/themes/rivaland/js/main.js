import Swiper from 'swiper/bundle';
import GLightbox from "glightbox";
import * as AOS from 'aos/dist/aos.js';
import { CountUp } from 'countup.js';
/** Include any other scripts here - this will combine them via Webpack for the final output script. */

import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { SplitText } from "gsap/SplitText";
import Lenis from 'lenis'

gsap.registerPlugin(ScrollTrigger, SplitText);

((window, document, $, undefined) => {

  /*******************************************************************************/
  /* MODULE
  /*******************************************************************************/

  const Base = (() => {

    /**
     * Runs when the document is ready.
     */
    const ready = () => {
      console.log('document ready!');

      AOS.init();

      const lightbox = GLightbox({
        touchNavigation: false,
        loop: false,
        autoplayVideos: true
      });

      // const swiper = new Swiper()
    };

    const smoothScrolling = () => {
      const lenis = new Lenis()
      
      lenis.on('scroll', ScrollTrigger.update)
      
      gsap.ticker.add((time)=>{
        lenis.raf(time * 1000)
      })
      
      gsap.ticker.lagSmoothing(0)

      function scrollToHash() {
        const hash = window.location.hash;
        
        if (hash) {
          const $targetElement = $(hash);
          const $siteHeader = $('.site-header');
      
          if ($targetElement.length && $siteHeader.length) {
            // Get the height of the site header
            const headerHeight = $siteHeader.outerHeight();
            
            // Calculate the target position, offset by the header height
            const targetPosition = $targetElement.offset().top - headerHeight;
      
            // Use Lenis to scroll to the target position
            lenis.scrollTo(targetPosition);
          }
        }
      }
      
      // Add hashchange event listener
      $(window).on('hashchange', function(event) {
        event.preventDefault();
        scrollToHash();
      });
      
      scrollToHash();
    }

    const headerJS = () => {
      $('.hamburger').on('click', function () {
        $('html').toggleClass('menu-active');
        $('.mobile-menu, .hamburger').toggleClass('active');
        $('html').toggleClass('html-overflow-hidden');
      })

      $(document).on('keydown', function(e) {
        if (e.key === "Escape" || e.keyCode === 27) {
          $('html').removeClass('menu-active html-overflow-hidden');
          $('.mobile-menu, .hamburger, .site-header').removeClass('active');
        }
      });

      $('.site-header__menu-mobile .menu-item-has-children >.main-menu-item button').on('click', function (e) {
        e.preventDefault();
        const curMenuItem = $(this).parents('.menu-item');
        if(!curMenuItem.hasClass('active')) {
            // $('.site-header .menu-item .sub-menu').stop().slideUp();
            $('.site-header .menu-item').removeClass('active')
        }
        // curMenuItem.find('.sub-menu').stop().slideToggle();
        curMenuItem.find('.sub-page').toggleClass('active');
        curMenuItem.toggleClass('active');
    
    });    

      // Handle clicking the back button to close the submenu
      $('.site-header__menu-mobile .sub-page .back').on('click', function (e) {
          e.preventDefault(); // Prevent the default action

          // Find the parent .sub-page and remove the .active class
          $(this).closest('.sub-page').removeClass('active');
      });

      var lastScroll = $(window).scrollTop();
    
      $(document).on("scroll", function () {
        var currentScroll = $(window).scrollTop();
        var scrollThreshold = $(window).width() < 768 ? 150 : 200;
    
        if (currentScroll > scrollThreshold) {
          $(".site-header").addClass("scrolling");
        } else {
          $(".site-header").removeClass("scrolling");
        }

        if (currentScroll) {
          $(".site-header").toggleClass("up", lastScroll > currentScroll);
        } else {
          $(".site-header").removeClass("up");
        }
    
        lastScroll = currentScroll;
      });
    };

    /**
     * Runs when the window is loaded.
     */
    const accordionJS = () => {
      const block = $('.accordions-wrapper');
      if (block.length) {
        const headings = block.find('.accordion-heading');
        const contents = block.find('.accordion-content');
        headings.on('click', function () {
          if (!$(this).hasClass('active')) {
            block.find('.accordion-heading.active').parent().find('.accordion-content').stop().slideUp()
            block.find('.accordion-heading.active').attr('aria-expanded', 'false');
            block.find('.accordion-heading.active').removeClass('active');
          }
          $(this).toggleClass('active')
          $(this).parent().find('.accordion-content').stop().slideToggle()
          if ($(this).attr('aria-expanded') == 'true') {
            $(this).attr('aria-expanded', 'false')
          } else {
            $(this).attr('aria-expanded', 'true')
          }
        })
      }
    };

    const countUp = () => {
      const observer = new IntersectionObserver(
          (entries, observer) => {
              entries.forEach((entry) => {
                  if (entry.isIntersecting) {
                      const $this = $(entry.target);
                      let rawStat = $this.data("stat"); // Get raw data-stat value
  
                      // Ensure rawStat is a string
                      if (typeof rawStat !== "string") {
                          rawStat = String(rawStat); // Convert to string if it's not
                      }
  
                      // Use regex to extract numeric value, prefix, and suffix
                      const match = rawStat.match(/^([^0-9\-]*)?([\d,\.]+)(.*)?$/); 
                      if (!match || !match[2]) {
                          console.error("Invalid data-stat format:", rawStat);
                          return;
                      }
  
                      const prefix = match[1] || ""; // Optional Prefix
                      const number = parseFloat(match[2].replace(/,/g, "")); // Numeric value
                      const suffix = match[3] || ""; // Optional Suffix
  
                      // Initialize CountUp options
                      const options = {
                          duration: 2.5,
                          useEasing: true,
                          useGrouping: true,
                          separator: ",",
                          decimal: ".",
                          prefix: prefix,
                          suffix: suffix,
                      };
  
                      // Initialize CountUp
                      const counter = new CountUp(entry.target, number, options);
  
                      if (!counter.error) {
                          counter.start();
                      } else {
                          console.error(counter.error);
                      }
  
                      // Stop observing once the animation has started
                      observer.unobserve(entry.target);
                  }
              });
          },
          { threshold: 1 } // Trigger when 100% of the element is visible
      );
  
      // Observe each .stat element and set initial values
      $(".stat").each(function () {
          const $this = $(this);
          let rawStat = $this.data("stat");
          
          if (typeof rawStat !== "string") {
              rawStat = String(rawStat);
          }
          
          const match = rawStat.match(/^([^0-9\-]*)?([\d,\.]+)(.*)?$/);
          if (match && match[2]) {
              const prefix = match[1] || "";
              const suffix = match[3] || "";
              $this.text(prefix + "0" + suffix);
          }
          
          observer.observe(this);
      });
    };   



    const load = () => {
      console.log('document load!');
    };

    /**
     * Return our module's publicly accessible functions.
     */
    return {
      ready: ready,
      smoothScrolling: smoothScrolling,
      headerJS: headerJS,
      accordionJS: accordionJS,
      countUp: countUp,
      load: load
    };

  })();

  /*******************************************************************************/
  /* MODULE INITIALISE
  /*******************************************************************************/

  jQuery(document).ready(function($) {
    Base.ready();
    Base.smoothScrolling(); // enable lenis smooth scrolling
    Base.accordionJS();
    Base.countUp();
    Base.headerJS();
  });

  jQuery(window).on('load', function($) {
    Base.load();
  });

})(window, document, jQuery);
