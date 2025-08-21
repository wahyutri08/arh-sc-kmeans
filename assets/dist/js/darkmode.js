// $(document).ready(function() {
//   // Kalau sebelumnya user sudah pilih dark mode, set checked
//   if ($('body').hasClass('dark-mode')) {
//     $('#customSwitch1').prop('checked', true);
//     $('.navbar').addClass('navbar-dark');
//     $('body').addClass('text-light');
//   }

//   // Event listener toggle
//   $('#customSwitch1').on('change', function() {
//     if ($(this).is(':checked')) {
//       $('body').addClass('dark-mode text-light');
//       $('.navbar').addClass('navbar-dark');
//       localStorage.setItem('darkMode', 'enabled');
//     } else {
//       $('body').removeClass('dark-mode text-light');
//       $('.navbar').removeClass('navbar-dark');
//       localStorage.setItem('darkMode', 'disabled');
//     }
//   });

//   // Saat reload, cek dari localStorage
//   if (localStorage.getItem('darkMode') === 'enabled') {
//     $('body').addClass('dark-mode text-light');
//     $('.navbar').addClass('navbar-dark');
//     $('#customSwitch1').prop('checked', true);
//   } else {
//     $('body').removeClass('dark-mode text-light');
//     $('.navbar').removeClass('navbar-dark');
//   }
// });
$(function () {
  var $header = $('.main-header'); // elemen navbar AdminLTE

  function applyDark(on) {
    if (on) {
      $('body').addClass('dark-mode text-light');
      $header.removeClass('navbar-white navbar-light').addClass('navbar-dark');
      localStorage.setItem('darkMode', 'enabled');
    } else {
      $('body').removeClass('dark-mode text-light');
      $header.removeClass('navbar-dark').addClass('navbar-white navbar-light');
      localStorage.setItem('darkMode', 'disabled');
    }
  }

  // init dari storage
  applyDark(localStorage.getItem('darkMode') === 'enabled');
  $('#customSwitch1').prop('checked', localStorage.getItem('darkMode') === 'enabled');

  // toggle
  $('#customSwitch1').on('change', function () {
    applyDark(this.checked);
  });
});
