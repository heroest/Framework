function draw_board()
{
	var index=0
	for(row=0; row<5; row++){
		current = $("<div class='hex-row'></div>").appendTo("div#game-board");
		if(row == 0 || row == 4) {
			if(row == 0) {
				x=0, y=2, z=-2;
			} else {
				x=-2, y=0, z=2;
			}
			for(col=0;col<3;col++) {
				node = $('<div class="hex" status="idle" data=' + index +  ' index=' + index + '>'
							+  '<div class="hex-head"></div>' 
							+  '<div class="hex-body"></div>'
							+  '<div class="hex-foot"></div>'
							+  '</div>').appendTo(current);
				node.attr('x', x);
				node.attr('y', y);
				node.attr('z', z);
				y--;
				x++;
				index++;
			}
		} else if(row == 1 || row==3) {
			if(row == 1) {
				x=-1, y=2, z=-1;
			} else {
				x=-2, y=1, z=1;
			}
			for(col=0; col<4; col++) {
				node = $('<div class="hex" status="idle" data=' + index +  ' index=' + index + '>'
							+  '<div class="hex-head"></div>' 
							+  '<div class="hex-body"></div>'
							+  '<div class="hex-foot"></div>'
							+  '</div>').appendTo(current);
				node.attr('x', x);
				node.attr('y', y);
				node.attr('z', z);
				y--;
				x++;
				index++;
			}
		} else {
			x=-2, y=2, z=0;
			for(col=0; col<5; col++) {
				node = $('<div class="hex" status="idle" data=' + index +  ' index=' + index + '>'
							+  '<div class="hex-head"></div>' 
							+  '<div class="hex-body"></div>'
							+  '<div class="hex-foot"></div>'
							+  '</div>').appendTo(current);
				node.attr('x', x);
				node.attr('y', y);
				node.attr('z', z);
				y--;
				x++;
				index++;
			}
		}
		
	}
}//end function draw_board()

function data_input(json_data)
{	
	data = JSON.parse(json_data);
	map  = data['map'];
	move = data['move'];
	for(i=0; i<map.length; i++) {
		v = map[i];
		s = (v=='x') ? 'disabled' : 'idle';
		n = $("div[index='" + i + "']");
		n.attr('data', v);
		n.attr('status', s);
	}
	update_board();
	click_bind();
}

function game_finish(type)
{
	$(".hex[status='idle']").unbind('click');
	switch(type) {
		case 'defeat':
			$(".moves")
				.removeClass('label-default')
				.addClass('label-danger')
				.text('Game-over!');
			break;

		case 'victory':
			$(".moves")
				.removeClass('label-default')
				.addClass('label-success')
				.text('victory!');
			break;
	}
}

function click_bind()
{	
	$(".hex")
		.unbind('click')
		.unbind('mouseenter')
		.unbind('mouseleave')
		.css('cursor', 'pointer');
	$(".hex[status='idle']")
		.click(function(){
			node = $(this);
			if($(this).attr('status') == 'idle') node_select(node);
		})
		.mouseenter(function(){
			node = $(this);
			if($(this).attr('status') == 'idle') flip_color( node, color_hover );
		})
		.mouseleave(function(){
			node = $(this);
			if($(this).attr('status') == 'idle') flip_color( node, color_idle );
		});
	$(".hex[status='disabled']").css('cursor','not-allowed');
}

function flip_color(node, color)
{
	node.find(".hex-head").css('border-bottom',    color + ' 30px solid');
	node.find(".hex-body").css('background-color', color);
	node.find(".hex-foot").css('border-top',       color + ' 30px solid');
}

function node_select(node)
{
	selected.push(node);
	node.attr('status', 'selected');

	if(swap.bootstrapSwitch('state') == true && selected.length == 2) {

		node_a = selected[0];
		node_b = selected[1];
		if(is_neighbour(node_a, node_b)) {
			node_swap(node_a, node_b);
		}
		reset_selected();
		swap.bootstrapSwitch('state', false);
		
	} else if(selected.length == 3) {

		if(is_inline(selected)) {
			thunder_express(selected);
		}
		reset_selected();

	}
	update_board();
}

function node_reset(node)
{
	node.attr('status', 'idle');
}

function reset_selected()
{
	selected.forEach(function(item, index, arr){
		if(item.attr('status') == 'selected') {
			node_reset(item);
		}
	});
	selected = [];
}

function is_neighbour(node_a, node_b)
{
	x1 = node_a.attr('x');
	y1 = node_a.attr('y');
	z1 = node_a.attr('z');

	x2 = node_b.attr('x');
	y2 = node_b.attr('y');
	z2 = node_b.attr('z');
	return Math.max(Math.abs(x1-x2), Math.abs(y1-y2), Math.abs(z1-z2)) == 1;
}

function is_inline(list_node)
{
	node_a = list_node[0];
	node_b = list_node[1];
	node_c = list_node[2];
	x1 = node_a.attr('x');
	y1 = node_a.attr('y');
	z1 = node_a.attr('z');

	x2 = node_b.attr('x');
	y2 = node_b.attr('y');
	z2 = node_b.attr('z');

	x3 = node_c.attr('x');
	y3 = node_c.attr('y');
	z3 = node_c.attr('z');

	return ((x3-x2) == (x2-x1)) && ((y3-y2) == (y2-y1)) && ((z3-z2) == (z2-z1));
}

function node_swap(node_a, node_b)
{
	a = node_a.attr('data');
	b = node_b.attr('data');
	node_a.attr('data', b);
	node_b.attr('data', a);
	move--;
	node_a.hide();
	node_b.hide();
	node_a.show('fade');
	node_b.show('fade');
}

function update_board()
{
	$(".hex[status='disabled']").css('cursor','not-allowed');
	$(".hex").each(function(){
		node = $(this);
		data  = '';
		color = '';

		status = node.attr('status');
		switch(status){
			case 'idle':
				data  = node.attr('data');
				color = color_idle;
				break;

			case 'selected':
				data  = node.attr('data');
				color = color_selected;
				break;

			case 'disabled':
				data  = 'x';
				color = color_disabled;
				break;

			case 'zero':
				data  = 0;
				color = color_zero;
				break;

		}
		node.removeAttr('exploded');
		node.removeAttr('shaked');
		node.find('.hex-body').text(data);
		flip_color(node, color);
		$(".moves").text(move);
	});
	if (move == 0) {
		game_finish('defeat');
	} else if($(".hex[status='idle']").length == 0) {
		game_finish('victory');
	}
}


function thunder_express(selected)
{
	node_a = selected[0];
	node_b = selected[1];
	node_c = selected[2];
	a = parseInt(node_a.attr('data'));
	b = parseInt(node_b.attr('data'));
	c = parseInt(node_c.attr('data'));
	old = c;

	if(a - b == c) {
		c--;
		move--;
	} else if(a + b == c) {
		c--;
		move--;
	} else if(a * b == c) {
		c--;
		move--;
	} else if(a == b * c) {
		c--;
		move--;
	}
	node_c.attr('data', c);
	if(c == 0) {
		node_zero(node_c);
	} else if(old != c) {
		node_bounce(node_c);
	}
	
}

function node_zero(node) 
{
	node_explode(node);
	node.attr('status', 'zero');
	$(".hex").filter(function(){
		status = $(this).attr('status');
		return is_neighbour(node, $(this)) && status != 'disabled' && status != 'zero';  
	}).each(function(){
		node_shake($(this));
		data = parseInt($(this).attr('data')) - 1;
		$(this).attr('data', data);
		if(data == 0) {
			node_zero($(this));
		}
	});

}


function node_explode(node)
{
	node.effect("explode", {pieces: 16}, 700, function(){
		node.show();
	});
	node.attr('exploded', 'True');
}

function node_bounce(node)
{
	if(! node.attr('exploded'))	node.effect("bounce", 400);
}

function node_shake(node)
{
	if(! node.attr('exploded') && ! node.attr('shaked')){
		node.effect("shake", {distance: 10, times: 2}, 600);
	}
	node.attr('shaked', 'True');
}