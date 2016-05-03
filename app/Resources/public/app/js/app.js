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
        inputTarget: 'digit-input',
        maxInputLength: 'digit-length'
    };

    $body.on('keypress', 'input[type=number]', function() {
        if ( this.value.length >=  this.max.length) {
            this.value =  this.value.slice(0,  this.max.length-1);
        }
    });
    //modal
    //$body.on('click', '.modal', e, function(e){
    //apply modal
    //});

    //Digit Key behaviours
    $body.on('click', 'form[data-'+digitAttributes.form+'] [data-'+digitAttributes.keyPad+']', function(){
        var $keyPressed = $(this),
            $input = $('[data-'+digitAttributes.inputTarget+']');
        var maxInputLength = 0;

        if ($keyPressed === undefined || $input === undefined) { //how to check that $input is an input
            return;
        }

        console.log$('form[data-'+digitAttributes.form+']').data(digitAttributes.maxInputLength);
        if ($('form[data-'+digitAttributes.form+']').data(digitAttributes.maxInputLength) !== undefined) {
            maxInputLength = $('form[data-'+digitAttributes.form+']').data(digitAttributes.maxInputLength);
        }

        var keyValue = $keyPressed.data(digitAttributes.keyPad);
        if (typeof keyValue !== 'number') {
            return;
        }

        if (keyValue < 0 || keyValue > 9) {
            return;
        }
        if (maxInputLength !== 0 && $input.val().length ===  maxInputLength) {
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
    //var startColor = '#FC5B3F';
    //var endColor = '#6FD57F';
    //
    //var element = document.getElementById('example-animation-container');
    //var circle = new ProgressBar.Circle(element, {
    //    color: startColor,
    //    trailColor: '#eee',
    //    trailWidth: 1,
    //    duration: 2000,
    //    easing: 'bounce',
    //    strokeWidth: 5,
    //
    //    // Set default step function for all animate calls
    //    step: function(state, circle) {
    //        circle.path.setAttribute('stroke', state.color);
    //    }
    //});
    //
    //circle.animate(1.0, {
    //    from: {color: startColor},
    //    to: {color: endColor}
    //});



})(jQuery);
/*
window.onload = function(){
    var storyId = '2_3';
    var campaingList = getCampaignList();
    for (i = 0; i < campaingList.length; i++) {
        extractInfoFromStory(campaingList[i]);
    }

    //extractInfoFromStory(storyId);
};
function getCampaignList()
{
    return ['1_1','1_2','1_3','1_4','1_5','2_1','2_2','2_3','2_4','2_5','2_6','2_7','3_1','3_2','3_3','3_4','3_5','3_6','3_7','3_8'];
    //return ['1_1','1_2','1_3','1_4','1_5'];
    //return ['2_1','2_2','2_3','2_4','2_5','2_6','2_7'];
    //return ['3_1','3_2','3_3','3_4','3_5','3_6','3_7','3_8'];
}
function extractInfoFromStory(storyId)
{
    var svg = $("#" + storyId).getSVG();
    var storyArray = createStoryArray(svg);
    var combatArray = createCombatArray(svg);
    var endingArray = createEndingArray(storyArray);
//console.log(endingArray);
//console.log(JSON.stringify(endingArray));
//console.log(Object.keys(endingArray).length);
//console.log(combatArray);
//console.log(JSON.stringify(combatArray));
//console.log(Object.keys(combatArray).length);
//console.log(storyArray);
//console.log(JSON.stringify(storyArray));
//console.log(Object.keys(storyArray).length);
    createLinks(storyId, endingArray, combatArray, storyArray);
}
function createStoryArray(svg)
{
    var storyArray = {};
    svg.find("g.edge title").each(function() {
        var text = $(this).html();
        if (text.length === 11) {
            var chapterValue = trimChapterValue(text.substr(0,3));
            var chapterDestinationValue = trimChapterValue(text.substr(8, 3));
            var newElement = [];
            if (storyArray[chapterValue] !== undefined) {
                storyArray[chapterValue].push(chapterDestinationValue);
                storyArray[chapterValue].sort(naturalCompare);
            } else {
                newElement[chapterValue] = [chapterDestinationValue];
            }
            storyArray = $.extend(storyArray, newElement);
        }
    });
    var lastElement = [];
    lastElement[350] = ['fin'];
    storyArray = $.extend(storyArray, lastElement);
    return storyArray;
}
function createCombatArray(svg) {
    var combatArray = {};
    svg.find("g.node a").each(function () {
        var text = '';
        var textArray = [];
        var iterator = 0;
        $(this).find('text').each(function () {
            textArray[iterator] = $(this).html();
            iterator++;
            text = text + $(this).html();
        });
        if (text.length > 14) {
            var chapterValue = trimChapterValue(textArray.pop());
            var newElement = [];
            var combatIterator = 0;
            var monsterName = '';
            var monsterCarac = '';
            for (j = 0; j < textArray.length; j++) {
                var textValue = textArray[j];
                if (isChapterValue(textValue) || isIllSmallValue(textValue)) {
                    continue;
                }
                if (isMonsterCaract(textValue)) {
                    monsterCarac = isMonsterCaract(textValue);
                }
                if (isMonsterName(textValue)) {
                    monsterName = isMonsterName(textValue);
                }
                if (monsterName !== '' && monsterCarac !== '') {
                    var combatInfo = getCombatInfo(monsterName, monsterCarac);
                    if (newElement[chapterValue] === undefined) {
                        newElement[chapterValue] = [];
                    }
                    newElement[chapterValue][combatIterator] = combatInfo;
                    monsterName = '';
                    monsterCarac = '';
                    combatIterator++;
                }
            }
            if (newElement !== []) {
                combatArray = $.extend(combatArray, newElement);
            }
        }
    });
    return combatArray;
}
function getCombatInfo(monsterName, monsterCarac){
    return { name : monsterName.replace('…','...'), 'COMBAT SKILL' : monsterCarac.CS, ENDURANCE : monsterCarac.EP};
}
function isChapterValue(text){
    var regex = /^(\d*)$/;
    var match;
    match = text.match(regex);
    if (match === null || Object.keys(match).length < 5) {
        return false;
    }
    return true;
}
function isIllSmallValue(text){
    var regex = /^(ill\d*|small\d*)$/;
    var match;
    match = text.match(regex);
    if (match === null || Object.keys(match).length < 5) {
        return false;
    }
    return true;
}
function isMonsterName(text){
    var regex = /^(.*):$/;
    var match, monsterName;
    match = text.match(regex);
    if (match === null || Object.keys(match).length < 1) {
        return '';
    }
    var monsterName = truncateMonsterName(match[1]);
    return monsterName;
}
function isMonsterCaract(text){
    var regex = /^( CS (\d*) EP (\d*))$/;
    var match, monsterCarac = {};
    match = text.match(regex);
    if (match === null || Object.keys(match).length < 2) {
        return '';
    }
    monsterCarac = { CS : match[2], EP : match[3]};
    return monsterCarac;
}
function createEndingArray(storyArray)
{
    var endingArray = {};
    var iterator = 1;
    while(iterator < 350) {
        if (storyArray[iterator] === undefined) {
            var newElement = [];
            newElement[iterator] = "Your life and your quest end here.";
            endingArray = $.extend(endingArray, newElement);
        }
        iterator++;
    }
    return endingArray;
}
function createLinks(storyId, endingArray, combatArray, storyArray) {
    createSaveLink("ending_js.json",endingArray, storyId);
    createSaveLink("combat_js.json",combatArray, storyId);
    createSaveLink("story_js.json",storyArray, storyId);
}
function naturalCompare(a, b) {
    var ax = [], bx = [];

    a.replace(/(\d+)|(\D+)/g, function(_, $1, $2) { ax.push([$1 || Infinity, $2 || ""]) });
    b.replace(/(\d+)|(\D+)/g, function(_, $1, $2) { bx.push([$1 || Infinity, $2 || ""]) });

    while(ax.length && bx.length) {
        var an = ax.shift();
        var bn = bx.shift();
        var nn = (an[0] - bn[0]) || an[1].localeCompare(bn[1]);
        if(nn) return nn;
    }

    return ax.length - bx.length;
}
function createSaveLink(fileName, data, storyId){
    var json = JSON.stringify(data);
    var blob = new Blob([json], {type: "application/json;charset=utf8"});
    var url  = URL.createObjectURL(blob);

    var div = document.createElement('div');
    var a = document.createElement('a');
    a.download    = storyId+fileName;
    a.href        = url;
    a.textContent = "Download " + storyId+fileName;
    //$('body').append(a);
    div.appendChild(a)
    document.getElementById('content').appendChild(div);
}
function trimChapterValue(chapterValue) {
    if (chapterValue.substr(0,2) === "00") {
        chapterValue = chapterValue.substr(2,1);
    }
    if (chapterValue.substr(0,1) === "0") {
        chapterValue = chapterValue.substr(1,2);
    }
    return chapterValue;
}
function truncateMonsterName(monsterName) {
    if (monsterName.length > 14) {
        monsterName = monsterName.substr(0, 11) + "...";
    }
    return monsterName;
}
*/