			<h1>Account DNS Check Report</h1>
			<p class="lead text-muted">{$reportname}.html generated {$reportdate|date_format:"%I:%M %p %H:%M:%S"}</p>
			<hr>

			<div class="panel panel-default">
				<table class="table table-condensed table-striped">
					<thead>
						<tr>
							<th>Username</th>
							<th>Domain</th>
							<th>Type</th>
							<th>Account IP</th>
							<th>Resolved To</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						{foreach $accounts as $k => $v}
						{foreach $v.domains as $kk => $vv}
						<tr>
							<td><a href="http://{$v.ip}/~{$k}/" title="view using mod_userdir" target="_blank">{$k}</td>
							<td><a href="http://{$kk}/" title="View {$kk}" target="_blank">{$kk}</a></td>
							<td>{if $vv.type eq "account"}Primary Domain{elseif $vv.type eq "parked"}Parked Domain{elseif $vv.type eq "addon"}Addon Domain{elseif $vv.type eq "sub"}Sub Domain{else}{$vv.type}{/if}</td>
							<td><a href="http://{$v.ip}/" title="View {$v.ip}" target="_blank">{$v.ip}</td>
							<td>{if $vv.resolvedto ne "Failed to Resolve"}<a href="http://{$vv.resolvedto}/" title="View {$vv.resolvedto}" target="_blank">{$vv.resolvedto}</a>{else}{$vv.resolvedto}{/if}</td>
							<td class="text-center {if $vv.status eq "ok"}success{else}danger{/if}">{$vv.status}</td>
						</tr>
						{/foreach}
						{foreachelse}
						<tr>
							<td colspan="6">No Results...</td>
						</tr>
						{/foreach}
					</tbody>
				</table>
			</div>

