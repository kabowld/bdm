$(function () {
    const map = $('#map');
    const paths = $('#map .map__image a');
    const links = $('#map .map__list a');

    let activeArea = function (id) {
        $('#map .is-active').each(function(i, v) {
            $(this).removeClass('is-active');
        })
        if (id !== undefined) {
            $('#list-' + id).addClass('is-active');
            $('#region-' + id).addClass('is-active');
        }
    }

    paths.each(function (index, path) {
        path.addEventListener('mouseenter', function (e) {
            let id  = this.id.replace('region-', '');
            activeArea(id);
        });
    });

    links.each(function (index, link) {
        link.addEventListener('mouseenter', function (e) {
            let id  = this.id.replace('list-', '');
            activeArea(id);
        });
    });

    map.on('mouseover', function () {
        activeArea();
    });

});
