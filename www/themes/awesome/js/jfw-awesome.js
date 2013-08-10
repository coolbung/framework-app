var JFWAwesome = {};

JFWAwesome.preview = function(text, preview, baseURL) {
	var out = $(preview);

	out.html('Loading preview...');

	$.post(baseURL + '/news/preview', { text: $(text).val() }, function(r) {
		out.empty();
		if (r.error) {
			out.html(r.error);
		} else if (!r.data.length) {
			out.html('Invalid response.');
		} else {
			out.html(r.data);
		}
	});
};
