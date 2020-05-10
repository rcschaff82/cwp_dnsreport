			<h1>Account DNS Check Report</h1>
			<p class="lead text-muted">{$reportname}.html generated {$reportdate|date_format:"%I:%M %p %H:%M:%S"}</p>
			<hr>

			<div class="panel panel-default">
				<table class="table table-condensed table-striped">
					<thead>
						<tr>
							<th>Username</th>
							<th>Reseller</th>
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
							<td>{if $v.reseller eq "1"}{else}{$v.reseller}{/if}</td>
							<td><a href="http://{$kk}/" title="View {$kk}" target="_blank">{$kk}</a></td>
							<td>{if $vv.type eq "account"}Primary Domain{elseif $vv.type eq "parked"}Parked Domain{elseif $vv.type eq "addon"}Addon Domain{elseif $vv.type eq "sub"}Sub Domain{else}{$vv.type}{/if}</td>
							<td>{$v.ip}</td>
							<td>{$vv.resolvedto}</td>
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

