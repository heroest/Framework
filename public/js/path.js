function drawLine(point_a, point_b, color)
{
	x1 = point_a['x'];
	y1 = point_a['y'];

	x2 = point_b['x'];
	y2 = point_b['y'];

	str = "<line x1='@x1' y1='@y1' x2='@x2' y2='@y2' style='stroke:@color;stroke-width:1;stroke-linecap=round'/>";
	str = str.replace('@x1', x1);
	str = str.replace('@y1', y1);
	str = str.replace('@x2', x2);
	str = str.replace('@y2', y2);
	str = str.replace('@color', color);
	return str;
}

function drawSVG(width, height, content, id)
{
	str = "<div class='route' id='@id'><svg width='@width' height='@height'>@content</svg></div>";
	str = str.replace('@id',      id);
	str = str.replace('@width',   width);
	str = str.replace('@height',  height);
	str = str.replace('@content', content);
	return str;
}

function drawPath()
{
	var device_x_zero = -1;
	var device_y_zero = -1;
	var sub_x_zero = -1;
	var sub_y_zero = -1;

	device = $("div.device").each(function(){

		id = $(this).attr('toggle');
		pos = $(this).offset();

		if(device_x_zero == -1) device_x_zero = pos['left'];
		if(device_y_zero == -1) device_y_zero = pos['top'];


		node_top = {'x': pos['left'] - device_x_zero + 50, 'y': 5};

		var sub_list = [];
		var lines = '';
		var svg   = '';

		var path_width  = 0;
		var path_height = 100;

		$("div.sub").each(function(){
			pos = $(this).offset();
			path_width = (pos['left'] > path_width) ? pos['left'] : path_width;
			if(sub_x_zero == -1) sub_x_zero = pos['left'];
			if(sub_y_zero == -1) sub_y_zero = pos['top'];
			sub_list.push({'x': pos['left'] - sub_x_zero + 37, 'y': 50});
		});

		node_mid = {'x': node_top['x'], 'y': 50};
		path = $(".path").css('width', path_width);

		lines += drawLine(node_top, node_mid, 'blue');

		sub_list.forEach(function(point, index, arr){
			lines += drawLine(node_mid, point, 'black');
			node = [];
			node['x'] = point['x'];
			node['y'] = 95;
			lines += drawLine(point, node, 'red');
		});

		svg = drawSVG(path_width, path_height, lines, id);
		path.append(svg);

	});
}