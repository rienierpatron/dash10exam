let base_url = "/sports-stars";

function process_player_listing() {
    let game_type = $('#game_type').val();
    let url = base_url + "/api.php?process=list&game_type="+game_type;
    $.get(url, function(response) {
        let players = JSON.parse(response);
        $(document).find('.sidenav').empty();
        $.each(players.list, function (key, player){
                let prev = (key == 0) ? players.list.length-1 : key - 1;
                let next = (key == players.list.length-1) ? 0 : key +1;
                let isactive = (key == 1) ? 'active' : '';
                let isvisible = ([0,1, 2].includes(key)) ? 'shown' : 'hidden';
                let name = (player.name) ? player.name : player.first_name+' '+player.last_name;
                $(document).find('.sidenav').append(`<div id="player_${key}" class="item ${isactive} ${isvisible}" data-key="${key}" data-id="${player.id}" data-prev="${prev}" data-next="${next}">${name}</div>`);
        });

        process_player_card(players.selected, game_type);

    }).fail(function() {
        console.log('Some thing went wrong. Please reload page.');
    });
}

function process_player_card(player, game_type) {

    $('#header').text(player.header);

    $.each(player, function(key, data) {
        if (key == 'number') {
            $(document).find('#'+key).text('#'+data);
        } else if (key == 'name') {
            $(document).find('#'+key).html(player.first_name + `<strong>${player.last_name}</strong>`);
        } else if (key == 'position') {
            $(document).find('#'+key).html(`<strong>Position</strong> ${player.position}`);
        } else if (key == 'weight') {
            $(document).find('#'+key).html(`<strong>Weight</strong> ${player.weight} KG`);
        } else if (key == 'height') {
            $(document).find('#'+key).html(`<strong>Height</strong> ${player.height}`);
        } else if (key == 'age') {
            $(document).find('#'+key).html(`<strong>Age</strong> ${player.age} YEARS`);
        } else if (key == 'featured') {
            $(document).find('.features').empty();
            $.each(player.featured, function(fKey, fData) {
                $(document).find('.features').append(`<div class="feature"  id="${fData.label}">`+
                                                        `<h3>${fData.label}</h3> ${fData.value}`+
                                                    `</div>`);
            });
        } else {
            $(document).find('#'+key).text(data);
        }
    });

    $('.logo').attr({
        src : player.logo,
        alt : player.current_team + ' logo'
    });

    $('.headshot').attr({
        src : player.image,
        alt : player.name
    });
}

function get_player_card(id) {
    let game_type = $('#game_type').val();
    let url = base_url + "/api.php?process=player&game_type="+game_type+"&id="+id;
    $.get(url, function(response) {
        let player = JSON.parse(response);
        process_player_card(player.selected);

    }).fail(function() {
        console.log('Some thing went wrong. Please reload page.');
    });
}

$(document).ready(function() {
    process_player_listing();

    $(document).on('click', '.item', function() {
        let id = $(this).data('id');
        let prev = $(this).data('prev');
        let next = $(this).data('next');

        let first_elemet = $(`#player_${prev}`);
        let second_element = $(this);
        let last_element = $(`#player_${next}`);

        $(`#player_${prev}`).remove();
        $(this).remove();
        $(`#player_${next}`).remove();
        $(document).find('.sidenav').prepend(first_elemet);
        $(document).find('.sidenav').prepend(second_element);
        $(document).find('.sidenav').prepend(last_element);
        $('.item').removeClass('shown active').addClass('hidden');

        $(`#player_${prev}`).removeClass('hidden').addClass('shown');
        $(this).removeClass('hidden').addClass('shown active');
        $(`#player_${next}`).removeClass('hidden').addClass('shown');

        get_player_card(id);
    });
});