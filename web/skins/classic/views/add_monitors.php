<?php
//
// ZoneMinder web function view file, $Date$, $Revision$
// Copyright (C) 2017 ZoneMinder LLC
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
//

if ( !canEdit( 'Monitors' ) ) {
  $view = 'error';
  return;
}

$focusWindow = true;
$navbar = getNavBarHTML();

xhtmlHeaders(__FILE__, translate('AddMonitors'));
?>
<body>
  <div id="page">
    <?php echo $navbar ?>
    <div id="content">

      <form name="contentForm" id="contentForm" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
        <div style="position:relative;">
        <div id="results" style="position: absolute; top:0; right: 0; width: 50%; height: 100%;">
          <fieldset><legend>Results</legend>
            <div id="url_results">
          
            </div>
          </fieldset>
        </div>
        <div style="width:50%;position: absolute; top:0; left: 0;height: 100%;">
        <fieldset><legend>Enter by IP or URL</legend>
          <!--<input type="text" name="newMonitor[Name]" />-->
          <input type="text" name="newMonitor[Url]" oninput="probe(this);"/>
        </fieldset>
        <fieldset><legend>Import CSV Spreadsheet</legend>
            Spreadsheet should have the following format:<br/>
            <table class="major">
              <tr>
                <th>Name</th>
                <th>URL</th>
                <th>Group</th>
              </tr>
              <tr title="Example Data">
                <td>Example Name MN1-30 INQ37.01</td>
                <td>http://10.34.152.20:2001/?action=stream</td>
                <td>MN1</td>
              </tr>
            </table>
            Defaults to apply to each monitor:<br/>
            <table><tr><th>Setting</th><th>Value</th></tr>
              <tr><td><?php echo translate('Function') ?></td><td>
<?php 
              $options = array();
              foreach ( getEnumValues('Monitors', 'Function') as $opt ) {
                $options[$opt] = translate('Fn'.$opt);
              }
              echo htmlSelect( 'newMonitor[Function]', $options, 'Record' );
?>
              </td></tr>
<?php
              $servers = Server::find_all();
              $ServersById = array();
              foreach ( $servers as $S ) {
                $ServersById[$S->Id()] = $S;
              }

              if ( count($ServersById) > 0 ) { ?>
              <tr class="Server"><td><?php echo translate('Server')?></td><td>
              <?php echo htmlSelect( 'newMonitor[ServerId]', array(''=>'Auto')+$ServersById, '' ); ?>
              </td></tr>
<?php
              }
              $storage_areas = Storage::find_all();
              $StorageById = array();
              foreach ( $storage_areas as $S ) {
                $StorageById[$S->Id()] = $S;
              }
              if ( count($StorageById) > 0 ) {
?>
<tr class="Storage"><td><?php echo translate('Storage')?></td><td>
<?php echo htmlSelect( 'newMonitor[StorageId]', array(''=>'All')+$StorageById, 1 ); ?>
</tr>
<?php
              }
?>
              </td></tr>
            </table>

            <input type="file" name="import_file" id="import_file"/>
            <input type="button" value="Import" onclick="import_csv(this.form);"/>
          </div>
          </div>
        </fieldset>
      </form>
    </div>
  </div>
<?php xhtmlFooter() ?>
