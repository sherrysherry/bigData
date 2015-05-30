<?php require dirname(__FILE__).'/header.php'?>
	<form class="chooseDataBase" action="plotGraph.php" enctype="text/plain">
		<table class="dataSet">
			<tr>
				<td colspan="2">
					<h1>Please choose a data set</h1>
				</td>
			</tr>
			<tr>
				<td>
					<input name="db" type="radio" value="dataVisualization_usaMessage" checked="" /> USA message 
				</td>
				<td>
					<h4>Complete data</h4>
					Data source: http://d3js.org/<br>
					Online message transmission of a social group<br> 
					Nodes: 3376 Edges: 5366<br>
				</td>
			</tr>
			<tr>
				<td>
					<input name="db" type="radio" value="dataVisualization_europeEmail" /> Email network
				</td>
				<td>
					<h4>Fraction data</h4>
					Data source: http://snap.stanford.edu/<br>
					Directed graph (each unordered pair of nodes is saved once)<br>
					Email network of a large Research Institution (sent between October 2003 and March 2005) <br>
					Nodes: 9996 Edges: 57290<br>
				</td>
			</tr>
			<tr>
				<td>
					<input name="db" type="radio" value="dataVisualization_SinaWeibo" /> Sina Weibo
				</td>
				<td>
					<h4>Fraction data</h4>
					Data source: http://hk.weibo.com//<br>
					Directed graph (each unordered pair of nodes is saved once)<br>
					Chinese microblogging (weibo) website <br>
					Nodes: 15597 Edges: 15757<br>
				</td>
			</tr>			
			<tr>
				<td colspan="2" style="text-align:center; border-bottom: 0px;">
					<br><input name="submitButton" type="submit" value="View Graph" />
				</td>
			</tr>				
		</table>
	</form>
<?php require dirname(__FILE__).'/footer.php'?>