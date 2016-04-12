/**
 * Created by Grumly on 21/02/2016.
 */
(function($){
    'use strict';

    //Todo create modules
    var $body = $('body');

    var digitAttributes = {
        form: 'digital-pad',
        keyPad: 'digit-key',
        eraseKey: 'erase-key',
        inputTarget: 'digit-input'
    };

    //modal
    //$body.on('click', '.modal', e, function(e){
    //apply modal
    //});

    //Digit Key behaviours
    $body.on('click', 'form[data-'+digitAttributes.form+'] [data-'+digitAttributes.keyPad+']', function(){
        var $keyPressed = $(this),
            $input = $('[data-'+digitAttributes.inputTarget+']');

        if ($keyPressed === undefined || $input === undefined) { //how to check that $input is an input
            return;
        }

        var keyValue = $keyPressed.data(digitAttributes.keyPad);
        if (typeof keyValue !== 'number') {
            return;
        }

        if (keyValue < 0 || keyValue > 9) {
            return;
        }

        var currentVal = $input.val(),
            newVal = (+currentVal*10+keyValue);
        $input.val(newVal);
    });

    //Erase Key behaviours
    $body.on('click', 'form[data-'+digitAttributes.form+'] [data-'+digitAttributes.eraseKey+']', function(){
        var $input = $('[data-'+digitAttributes.inputTarget+']');
        if ($input === undefined) { //how to check that it's an input
            return;
        }
        var currentVal = $input.val(),
            newVal = currentVal.substr(0, currentVal.length-1);
        if (currentVal.length < 1) {
            return;
        }
        $input.val(newVal);
    });

    $body.on('click', '[data-form-sumbit]', function(){
        var $button = $(this),
            $formTarget = $($button.data('form-sumbit'));
        $formTarget.submit();
    });

    // This is a test for filerev

    //progressive bar
    /*
     var startColor = '#FC5B3F';
     var endColor = '#6FD57F';

     var element = document.getElementById('example-animation-container');
     var circle = new ProgressBar.Circle(element, {
     color: startColor,
     trailColor: '#eee',
     trailWidth: 1,
     duration: 2000,
     easing: 'bounce',
     strokeWidth: 5,

     // Set default step function for all animate calls
     step: function(state, circle) {
     circle.path.setAttribute('stroke', state.color);
     }
     });

     circle.animate(1.0, {
     from: {color: startColor},
     to: {color: endColor}
     });
     */



})(jQuery);
