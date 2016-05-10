//
// Server Settings Class
//
class ServerSettingsClass
{
	constructor()
	{
		this.watch();
	}

	//
	// Watch stuff
	//
	watch()
	{
		$('.server-group h2').on('click', (event) => {
			var $group = $(event.currentTarget);
			$group.parent().find('> div').toggleClass('active');
			$group.find('.fa').toggleClass('fa-plus').toggleClass('fa-minus');
		});

		$('.form-expand-all').on('click', (event) => {
			$('.server-group').each((i, element) => {
				var $group = $(element);
				$group.find('> div').addClass('active');
				$group.find('.fa').removeClass('fa-plus').addClass('fa-minus');
			})
		});

		$('.btn-server-restart').on('click', (event) => {
			$('#saveServerSettingsModal').modal('show');

			var timer = setInterval(() => {
				var $countdown = $('.restart-countdown em'),
					number = parseInt($countdown.text());

				number = number - 1;
				if (number < 1) {
					clearInterval(timer);
				}

				$countdown.text(number > 0 ? number : 'Complete. Refreshing page ...');
			}, 1000);
		});
	}
}
