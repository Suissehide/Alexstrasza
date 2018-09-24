(function ($) {

    function bit16() {
        window.obj = {
            global: '16-bit'
        }
        $('.mode div:nth-child(2)').addClass('selected');
        $('html').css({
            'background-image': 'radial-gradient(rgba(96,0,255,.25),rgba(96,0,255,.05))'
        })
        $('#starfield').html('').starscroll(16, 3, 50, 5, 4, [96, 255, 255], true, true, '16-bit');
    }
    function bit8() {
        window.obj = {
            global: '8-bit'
        }
        $('.mode div:nth-child(1)').addClass('selected');
        $('html').css({
            'background-image': 'radial-gradient(rgba(175,220,130,.25),rgba(175,220,130,.05))'
        })
        $('#starfield').html('').starscroll(8, 3, 30, 4, 4, [96, 255, 255], true, true, '8-bit');
    }
    function manual() {
        window.obj = {
            global: 'manual'
        }
        $('h3.nst').html('START SCROLLING!');
        $('h3.rights').hide(100);
        // $('.manual').show(300);
        // $('.manual').css({'display': 'flex'});
        $('html').css({
            'background-image': 'radial-gradient(rgba(194,43,87,.25),rgba(194,43,87,.05))'
        });
        $('.mode div:nth-child(3)').addClass('selected');
        $('#starfield').html('').starscroll(16, 5, 35, 4, 2, [255, 255, 0], false, false, 'manual');
    }
    function reset() {
        $('h3.nst').html('INSERT COIN');
        $('h3.rights').show(100);
        // $('.manual').hide(0);
        $('.mode').children('div').each(function (i) {
            $this = $(this);
            $this.removeClass('selected');
        })
    }
    $('.mode').children('div').each(function (i) {
        $this = $(this);
        $this.on('click', function () {
            var j = i + 1;
            reset();
            switch (j) {
                case 1:
                    bit8();
                    break;
                case 2:
                    bit16();
                    break;
                case 3:
                    manual();
                    break;
            }
        })
    })
    var isMobile = {
        Android: function () {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function () {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function () {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function () {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function () {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function () {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };
    if (!isMobile.any()) {
        manual();
    } else {
        $('.mode').hide(0);
    }
})(jQuery);