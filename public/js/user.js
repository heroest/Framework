function input_help_text(node, mark, text)
{
	input_help_text_clear(node);
	node.attr('aria-describedby', mark);
	str = '<span id="' + mark + '" class="help-block col-sm-offset-3 col-sm-9">' + text + '</span>';
	parent.append(str);
}

function input_help_text_clear(node)
{
	node.removeAttr('aria-describedb');
	parent = node.closest('div.form-group');
	parent.find('span.help-block').each(function(){$(this).remove();});
}

function input_help_state(node, state)
{
	input_help_state_clear(node);
	var parent = node.closest('div.form-group');
	switch(state){
		case 'success':
			
			parent
				.addClass('has-success')
				.addClass('has-feedback');
			break;

		case 'error':
			parent
				.addClass('has-error')
				.addClass('has-feedback');
			break;
	}
}

function input_help_state_clear(node)
{
	var parent = node.closest('div.form-group');
	parent.attr('class', 'form-group');
}

function btn_disable(btn)
{
	btn.attr('disabled', 'disabled');
}

function btn_enable(btn)
{
	btn.removeAttr('disabled');
}