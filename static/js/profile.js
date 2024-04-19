/*  
@@@@@@ @@@@@@  @@@      @@@@@@@      @@@@@@@  @@@@@@@@ @@@  @@@
@@         @@! @@!        @@!        @@!  @@@ @@!      @@!  @@@
!@@!!   @!!!:  @!!        @!!        @!@  !@! @!!!:!   @!@  !@!
   !:!     !!: !!:        !!:        !!:  !!! !!:       !: .:! 
::.: :  ::: ::  : ::.: :    :         :: :  :  : :: :::    ::   

link  https://github.com/S3LTDev/S3LT-Social-Template
author  max2tz https://github.com/S3LT
license  GPL-3.0 License
*/
let editMode = false

function convertEditMode(mode) {
	if (editMode == mode) return

	if (mode) {
		$("#form-displayname").val($("#display-name").text())
		$('#edit-mode').show()
		$('#display-mode').hide()
	} else {
		$('#edit-mode').hide()
		$('#display-mode').show()
	}

	editMode = mode
}

function updateProfile() {
	const url = "../api.user.info/" + userId
	$.getJSON(url, function (data) {
		if (data.status == "success") {

			$("#display-wrapper").show()
			$("#status-wrapper").show()
			$("#gems-wrapper").show()
			$("#badges-wrapper").show()

			$("#inventory-wrapper").show()
			$("#events-wrapper").show()
			$("#posts-wrapper").show()

			$("#gems-wrapper #gems").html(
				'<span title="' + String(data.gems) + '">' + String(nFormatter(data.gems, 1)) + "</span>"
			)

			$("#display-name").html(data.displayname)
			$("#user-name").html("@" + data.username)

			if (data.online === 1) {
				$("#status-wrapper #status").html('<span style="color: rgb(111, 192, 111);">Online</span>')
			} else if (data.online === 2) {
				$("#status-wrapper #status").html('<span style="color: rgb(191, 192, 111);">Idle</span>')
			} else {
				$("#status-wrapper #status").html('<span style="color: rgb(192, 111, 111);">Offline</span>')
			}

			$("#badges").empty()
			$("#inventory").empty()
			$("#posts").empty()
			$("#events").empty()

			$.each(data.badges, function (key, val) {
				$("#badges").append("<span>" + val.badge + "</span>")
			})

			if (data.badges.length > 3) {
				$("#badges").append(
					'<span class="more-badge" style="display: none;">+</span>'
				)
			}

			$.each(data.inventory, function (key, val) {
				$("#inventory").append(
					`<div class="project-box-wrapper">
						<div class="project-box random-blue">
							<div class="project-box-header">
								<span>${val.name}</span>
							</div>
							<div class="project-box-content-header">
								<img class="inventory-img" src="${val.asset}" alt="">
							</div>
							<div class="box-progress-wrapper">
								<p class="box-progress-header">${val.rarity}</p>
							</div>
						</div>
					</div>`
				)
			})

			$.each(data.posts, function (key, val) {
				$("#posts").append(
					`<a href="">
						<div class="project-box-wrapper">
							<div class="project-box random-blue">
								<div class="project-box-header">
									<span>${val.content}</span>
								</div>
								<div class="project-box-content-header">
									<p class="box-content-header">${val.title}</p>
								</div>
							</div>
						</div>
					</a>`
				)
			})

			$.each(data.events, function (key, val) {
			let event_datetime = new Date(val.start_timestamp)
			let current_datetime = Date.now()

			if (event_datetime > current_datetime) {
				$("#events").append(
					`<div class="project-box-wrapper" id="' + val.game + '">
						<div class="project-box random-blue">
							<div class="project-box-header">
								<span>${moment(event_datetime).format("MMM Do YY | h:mm a")}</span>
							</div>
							<div class="project-box-content-header">
								<p class="box-content-header">${val.game}</p>
								<p class="box-content-subheader">${val.platform}</p>
							</div>
							<div class="project-box-footer">
								<div class="participants" title="Host: ${val.host.username}">
									<img src="${val.host.avatar}" alt="participant">
								</div>
								<div class="badge-storage">
									<div class="badge-trans">
										Starts in ${moment(event_datetime).fromNow()}
									</div>
									<div class="badge-trans">
										<a href="">Join now</a>
									</div>
								</div>
							</div>
						</div>
					</div>`
				)
			} else {
				$("#events").append(
					`<div class="project-box-wrapper" id="${val.game}">
						<div class="project-box random-blue">
							<div class="project-box-header">
								<span>${moment(event_datetime).format("MMM Do YY | h:mm a")}</span>
							</div>
							<div class="project-box-content-header">
								<p class="box-content-header">${val.game}</p>
								<p class="box-content-subheader">${val.platform}</p>
							</div>
							<div class="project-box-footer">
								<div class="participants" title="Host: ${val.host.username}">
									<img src="${val.host.avatar}" alt="participant">
								</div>
								<div class="badge-storage">
									<div class="badge-trans">
										Hosted ${moment(event_datetime).fromNow()}
									</div>
								</div>
							</div>
						</div>
					</div>`
				)
			}
			})
		} else {
			$("#error-wrapper").show()
		}
	}).done(function () {
		colorRandomBlue()
	})
}

document.addEventListener("DOMContentLoaded", function () {
	updateProfile()

	setInterval(function () {
		updateProfile()
	}, 30000)

	$("#page-title").remove()
	$("#edit-mode").hide()

	$("#edit-display").click(function () {
		convertEditMode(1)
	})

	$("#cancel-display").click(function () {
		convertEditMode(0)
	})

	$("#save-display").click(function () {
		let displayname = $('#form-displayname').val()
		
		if ( confirm("Are you sure?") ) {
			$.post("../api.user.displayname", 
				{ displayname },
				function(data, status) {
					if (data.status == 'success' && status == 'success') {
						infoSnackBar('Your display name is updated successfully.')
						$("#display-name").html(displayname)
						convertEditMode(0)
					} else {
						errorSnackBar(data.message)
					}
				}
			)
		}
	})
})
