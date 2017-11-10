/* global prayer */

/**
 * get and display a random hadith from server
 * It will be shwon every 5 min, except in prayer moment
 */
var randomHadith = {
    init: function () {
        if (prayer.confData.randomHadithEnabled) {
            setInterval(function () {
                if (!prayer.isPrayingMoment()) {
                    randomHadith.get();
                    setTimeout(function () {
                        randomHadith.hide();
                    }, 2 * prayer.oneMinute);
                }
            }, 5 * prayer.oneMinute);
        }
    },
    get: function () {
        if ($(".main").is(":visible")) { // condition to bypass a display bug
            $randomHadithEl = $(".random-hadith");
            $.ajax({
                type: "JSON",
                url: $randomHadithEl.data("remote"),
                success: function (resp) {
                    if (resp.text !== "") {
                        $(".random-hadith .text div").text(resp.text);
                        randomHadith.show(randomHadith.setFontSize, resp.lang);
                    }
                },
                error: function () {
                    randomHadith.hide();
                    var hadith = $(".random-hadith .text div").text();
                    if (hadith != "") {
                        randomHadith.show();
                    }
                }
            });
        }
    },
    show: function (callback, lang) {
        prayer.nextPrayerCountdown();
        $(".top-content").fadeOut(1000, function () {
            $(".footer").hide();
            $(".random-hadith").fadeIn(1000);
            if (typeof callback !== 'undefined' && typeof lang !== 'undefined') {
                callback(lang);
            }
        });
    },
    hide: function () {
        $(".random-hadith").fadeOut(1000, function () {
            $(".footer").show();
            $(".top-content").fadeIn(1000);
        });
    },
    setFontSize: function (lang) {
        var $textContainer = $('.random-hadith .text');
        var $textContainerDiv = $('.random-hadith .text div');
        $textContainerDiv.css('font-size', '150px');
        $textContainerDiv.css('line-height', '300px');
        if (lang !== "ar") {
            $textContainerDiv.css('line-height', '140%');
        }
        while ($textContainerDiv.height() > $textContainer.height()) {
            $textContainerDiv.css('font-size', (parseInt($textContainerDiv.css('font-size')) - 1) + "px");
            if (lang === "ar") {
                $textContainerDiv.css('line-height', (parseInt($textContainerDiv.css('line-height')) - 2) + "px");
            }
        }
    }
};
