/**
 * ownCloud - mydataapp
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author susan <liangxueping2015@gmail.com>
 * @copyright susan 2016
 */

(function ($, OC) {

	$(document).ready(function () {
		$('#hello').click(function () {
			alert('Hello from your script file');
				
		});

		$('#echo').click(function () {
			var url = OC.generateUrl('/apps/mydataapp/echo');
			var data = {
				echo: $('#echo-content').val()
			};

			$.post(url, data).success(function (response) {
				$('#echo-result').text(response.echo);
			});

		});
		
		$('#upload').click(function () {
			var url = OC.generateUrl('/apps/mydataapp/upload');
			var data = {
					echo: $('#dataRecord').val()
				};
			
			$.post(url, data).success(function (response) {
				$('#uploadResult').text(response.echo);
			});
		});
	});

})(jQuery, OC);
