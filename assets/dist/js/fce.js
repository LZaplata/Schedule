$(document).ready(function () {
    var x, left, down;
    var room = $(".schedule-wrapper .rooms .room:first-child");
    var active = $(".schedule-wrapper .rooms .room .block.active").get(0);
    var screenWidth = screen.width;
    var nameWidth = room.children(".name").outerWidth();
    
    if (active) {
        var activeOffset = parseInt(active.style.left) + parseInt(active.lastElementChild.offsetWidth) + room.offset().left + nameWidth;

        $(".schedule-wrapper").scrollLeft(activeOffset - (screenWidth / 2));
    } else {
        if ($(".schedule-wrapper .rooms.ended").length) {
            $(".schedule-wrapper").scrollLeft($(".schedule").outerWidth());
        }
    }

    $(".schedule-wrapper").mousedown(function (e) {
        e.preventDefault();

        down = true;
        x = e.pageX;
        left = $(this).scrollLeft();
    });

    $("body").mousemove(function (e) {
        if (down) {
            var newX = e.pageX;

            $(".schedule-wrapper").scrollLeft(left - newX + x);
        }
    });

    $("body").mouseup(function (e) {
        down = false;
    });
});