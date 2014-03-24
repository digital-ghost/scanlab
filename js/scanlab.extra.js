/*
*	Sveestelki & perdelki
*/

// create new report div
function formatReport(row, new_item) {
	if (new_item === true) {
		html = '<div class="report-row box small box-hl" id="'+row._id.$id+'">';
	} else {
		html = '<div class="report-row box small" id="'+row._id.$id+'">';
	}

	html += '<div class="report-header">';
	if (row.report.geoip.country !== undefined) {
		html += '<img class="flag" src="'+rel_url+'images/png/'
			+ x(row.report.geoip.country.toLowerCase())+'.png" title="'
			+ x(row.report.geoip.country)+'"> ';
	}

	if (row.report.address !== undefined) {
		html += x(row.report.address);
	}

	html += ' <span class="mute">by '+x(row.user)+'</span> ';

	if (row.tags.length !== 0) {
		$.each(row.tags, function(i, v) {
			html += "["+x(v) + "] ";
		});
	}

	html += '<span class="item-link">'
	+'<a target="_blank" href="'+rel_url+'id/'+row._id.$id+'" class="btn">view</a></span>';

	html += '</div>'; // end header

	//tags
	html += '<div class="report-ports">';

	if (row.report.ports.length !== 0) {
		// add ports
		$.each(row.report.ports, function(i, v){
			if (v.state === "open") {
				html += '<span class="small port port-open">' + x(v.portid) + '/' +x(v.service.name) + '</span> ';
			}
		});

	}
	html += '</div>';
	// ports
	
	html += '</div>';
	return html;
}

$(document).ready(function(){
	// set time of last update
	last_time = time_seconds();

	url = location.pathname.replace(rel_url, "");
	if (url === "") {
		$.post(rel_url+"ajax", {action: "get_last"}).done(function(data){
			reports = $.parseJSON(data);
			$.each(reports, function(i, report){
				report_html = formatReport(reports[i]);
				$("#live-results").append(report_html);
			});
		});

		setInterval(function(){
			$.post(rel_url+"ajax", {action: "get_last", update: last_time}).done(function(data){
				reports = $.parseJSON(data);
				if (reports.length !== 0) {
					//set NEW time and render new
					last_time = time_seconds();
					$.each(reports, function(i, report){
						report_html = formatReport(reports[i], true);
						$("#live-results").prepend(report_html);
					});
				}
			});
		}, 5000);
	}



});

$(document).on("mouseover", ".box-hl", function(){
	$(this).removeClass("box-hl");
});