function game_init(num)
{
	//reset all house and store
	$("td").attr('hold', 0).empty().unbind('click');
	$("div.message").empty();
	if(num < 2) {
		console.log('fired');
		$("div.message").text('Seed must >= 2');
		return;
	}
	for(i=0;i<2;i++) {
		x = random_int(1, 6);
		y = i;
		target = "td.house[x='" + x + "'][y='" + y + "']";
		$(target).attr('hold', 1);
		num--;
	}

	while(num > 0) {
		x = random_int(1, 6);
		y = random_int(0, 1);
		target = "td.house[x='" + x + "'][y='" + y + "']";
		node = $(target);
		value = parseInt(node.attr('hold'));
		value++;
		node.attr('hold', value);
		num--;
	}
	$("div.console-panel").empty();
	game_board_update();
}

function game_board_update()
{
	player_house = [];
	AI_house = [];
	$("td").each(function(){
		node = $(this);
		str = '';
		count = 0;
		value = parseInt(node.attr('hold'));
		while(value > 0) {
			if(count%5 == 0 && count != 0) str += '<br />';
			str += seed_icon;
			value--;
			count++;
		}
		node.html(str);
		if(count != 0) {
			if(node.hasClass('house Player')) player_house.push(node);
			if(node.hasClass('house AI')) AI_house.push(node);
		}
	});
	if(player_house.length == 0) {
		game_finish('Player');
	} else if(AI_house.length == 0) {
		game_finish('AI');
	}
}

function game_move(node)
{
	value = parseInt(node.attr('hold'));
	count = value
	node.attr('hold', 0);
	current = node;
	while(count > 0) {
		current = game_next_node(current);
		current.show('highlight', 'slow');
		current.attr('hold', parseInt(current.attr('hold')) + 1);
		count--;
	}
	if(current.hasClass('house ' + playing) && current.attr('hold') == 1) {
		game_capture(current);
	}
	if(value !== 0) {
		if(!current.hasClass('store')) {
			game_switch_playing();
		}
	}
}

function game_finish(role)
{
	clean_role = (role == 'Player') ? 'AI' : 'Player';
	total = 0;
	$("td.house." + clean_role).each(function(){
		node = $(this);
		total += parseInt(node.attr('hold'));
		node.attr('hold', 0).empty();
	});
	total += parseInt($("td.store." + clean_role).attr('hold'));
	$("td.store." + clean_role).attr('hold', total);
	player_total = parseInt($("td.store.Player").attr('hold'));
	AI_total = parseInt($("td.store.AI").attr('hold'));

	if(player_total > AI_total) {
		$("td.store.Player").empty().text('Victory: ' + player_total);
		$("td.store.AI").empty().text("Defeat: " + AI_total);
	} else if(AI_total > player_total) {
		$("td.store.Player").empty().text('Defeat: ' + player_total);
		$("td.store.AI").empty().text('Victory: ' + AI_total);
	} else {
		$("td.store.AI").text('Draw: ' + AI_total);
		$("td.store.Player").text('Draw: ' + player_total);
	}

	$("td.house").unbind('click');
}

function game_next_node(node)
{
	x = parseInt(node.attr('x'));
	y = parseInt(node.attr('y'));

	if(y == 1) {
		x++;
		if((x == 7 && playing != 'Player') || x > 7) {
			y = 0;
			x = 6;
		}
	} else {
		x--;
		if((x == 0 && playing != 'AI') || x < 0) {
			y = 1;
			x = 1;
		}
	}
	target = "td[x='@x'][y='@y']";
	target = target.replace('@x', x);
	target = target.replace('@y', y);
	return $(target);
}

function random_int(min, max) 
{
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function game_switch_playing()
{
	playing = (playing == 'Player') ? 'AI' : 'Player';
	$("th.score").css("background-color", "white");
	$("th." + playing).css("background-color", "red");
}

function game_capture(node)
{
	x = node.attr('x');
	y = Math.abs(node.attr('y') - 1);
	
	target = "td[x='@x'][y='@y']";
	target = target.replace('@x', x);
	target = target.replace('@y', y);

	node_opp = $(target);
	if(node_opp.attr('hold') != 0 && node.attr('hold') == 1) {
		cur = $("td.store." + playing);
		val = parseInt(cur.attr('hold')) + parseInt(node_opp.attr('hold')) + parseInt(node.attr('hold'));
		cur.attr('hold', val);
		node_opp.effect('highlight', 'slow').attr('hold', 0);
		node.attr('hold', 0);
	}
}

function game_serialize()
{
	data = [];
	$("td.house").each(function(){
		node = $(this);
		x = node.attr('x');
		y = node.attr('y');
		hold = node.attr('hold');
		point = {};
		point['x'] = x;
		point['y'] = y;
		point['value'] = hold;
		data.push(point);
	});
	return JSON.stringify(data);
}

function game_console_log(msg)
{
	str = "<p class='text-info'>@msg</p>";
	str = str.replace('@msg', msg);
	$("div.console-panel").prepend(str);
}

function AI_status_toggle()
{
	AI = $("div.panel-heading");
	status = AI.attr('status');
	if(status == 'standby') {
		AI.attr('status', 'working').text('AI is calculating...');
		AI.closest('div.panel').switchClass('panel-success', 'panel-danger', 10);
	} else {
		AI.attr('status', 'standby').text('AI is Standing By...');
		AI.closest('div.panel').switchClass('panel-danger', 'panel-success', 10);
	}
}

function game_AI()
{
	AI_status_toggle();
	$.get('/security/getCSRF', function(json){
		csrf = JSON.parse(json);
		post_data = "game_data=@game_data&@csrf_name=@csrf_token";
		post_data = post_data.replace("@game_data", game_serialize());
		post_data = post_data.replace("@csrf_name", csrf['name']);
		post_data = post_data.replace("@csrf_token", csrf['token']);
		$.post('/game/kalah/AI', post_data, function(json){
			if(! $.isEmptyObject(json)) {
				AI_status_toggle();
				data = JSON.parse(json);
				x = data['x'];
				y = data['y'];
				game_console_log(data['log'] + ', AI click on ' + x + ',' + y);
				target = "td[x='@x'][y='@y']";
				target = target.replace('@x', x);
				target = target.replace('@y', y);
				$(target).effect('highlight', 'slow').trigger('ai_click');
			}
		});
	});
}