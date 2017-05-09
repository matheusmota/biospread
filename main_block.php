<br/><table  width="1000px" class="table">
	<tr>
		<td width="100px" style="vertical-align: top" align="right">
		<? include './layout1/menu.php';?>
		</td>
		<td width="900px" align="left" style="padding-left: 100px"> <?
		if (!isset($_GET["task"])) {
			include 'pages/statistics.php';
		}else {
			$task = $_GET["task"];
			try{
                    include $task.".php";
                } catch (Exception $e) {
                    include "pages/statistics.php";
                }
		}
		?></td>
	</tr>

</table>
