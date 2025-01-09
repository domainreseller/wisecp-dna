<?php
if (!defined("CORE_FOLDER")){die();}
/** @var  $module  DomainNameAPI*/
$LANG   = $module->lang;
$CONFIG = $module->config;
Helper::Load("Money");
$soap_exists = class_exists("SoapClient");
?>


<div id="dna-tab">
    <ul class="modules-tabs">
        <li>
            <a href="javascript:DNAOpenTab(this,'detail');" data-tab="detail" class="modules-tab-item active"><?php echo $LANG["tabDetail"]; ?></a>
        </li>
        <li class="mod-show-ready" style="display: none">
            <a href="javascript:DNAOpenTab(this,'import');" data-tab="import" class="modules-tab-item"><i class="fa fa-code-fork"></i> <?php echo $LANG["tabImport"]; ?></a>
        </li>
        <li class="mod-show-ready" style="display: none">
            <a href="javascript:DNAOpenTab(this,'tlds');" data-tab="tlds" class="modules-tab-item"><i class="fa fa-fire"></i> <?php echo $LANG["tabImportTld"]; ?></a>
        </li>
    </ul>

    <div id="dna-tab-detail" class="modules-tabs-content" style="display: block">

        <?php
        if (!$soap_exists) {
            ?>
            <div class="red-info">
                <div class="padding10">
                    <?php echo $LANG["error7"]; ?>
                </div>
            </div>
            <?php
        }
        ?>

        <form action="<?php echo Controllers::$init->getData("links")["controller"]; ?>" method="post" id="DomainNameAPISettings">
            <input type="hidden" name="operation" value="module_controller">
            <input type="hidden" name="module" value="DomainNameAPI">
            <input type="hidden" name="controller" value="settings">

            <div class="formcon">
                <div class="yuzde30"><?php echo $LANG["fields"]["username"]; ?></div>
                <div class="yuzde70">
                    <input type="text" name="username" value="<?php echo $CONFIG["settings"]["username"]; ?>">
                </div>
            </div>

            <div class="formcon">
                <div class="yuzde30"><?php echo $LANG["fields"]["password"]; ?></div>
                <div class="yuzde70">
                    <input type="password" name="password" value="<?php echo $CONFIG["settings"]["password"] ? "*****" : ""; ?>">
                </div>
            </div>

            <div class="formcon" style="display: none">
                <div class="yuzde30"><?php echo $LANG["fields"]["resellerid"]; ?></div>
                <div class="yuzde70">
                    <input type="text" name="resellerid" value="<?php echo $CONFIG["settings"]["resellerid"]; ?>">
                </div>
            </div>

            <div class="formcon" style="display: none">
                <div class="yuzde30"><?php echo $LANG["fields"]["api-v2"]; ?></div>
                <div class="yuzde70">
                    <input<?php echo isset($CONFIG["settings"]["api-version"]) && $CONFIG["settings"]["api-version"] ? ' checked' : ''; ?> type="checkbox" name="api-version" value="1" id="DomainNameAPI_apiv2" class="checkbox-custom">
                    <label class="checkbox-custom-label" for="DomainNameAPI_adp">
                        <span class="kinfo"><?php echo $LANG["desc"]["api-v2"]; ?></span>
                    </label>
                </div>
            </div>

            <div class="formcon">
                <div class="yuzde30"><?php echo $LANG["fields"]["privacyFee"]; ?></div>
                <div class="yuzde70">
                    <input type="text" name="whidden-amount" value="<?php echo Money::formatter($CONFIG["settings"]["whidden-amount"], $CONFIG["settings"]["whidden-currency"]); ?>" style="width: 100px;" onkeypress='return event.charCode==46  || event.charCode>= 48 &&event.charCode<= 57'>
                    <select name="whidden-currency" style="width: 150px;">
                        <?php
                        foreach (Money::getCurrencies($CONFIG["settings"]["whidden-currency"]) as $currency) {
                            ?>
                            <option<?php echo $currency["id"] == $CONFIG["settings"]["whidden-currency"] ? ' selected' : ''; ?> value="<?php echo $currency["id"]; ?>"><?php echo $currency["name"] . " (" . $currency["code"] . ")"; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <span class="kinfo"><?php echo $LANG["desc"]["privacyFee"]; ?></span>
                </div>
            </div>

            <div class="formcon">
                <div class="yuzde30"><?php echo $LANG["fields"]["adp"]; ?></div>
                <div class="yuzde70">
                    <input<?php echo isset($CONFIG["settings"]["adp"]) && $CONFIG["settings"]["adp"] ? ' checked' : ''; ?> type="checkbox" name="adp" value="1" id="DomainNameAPI_adp" class="checkbox-custom">
                    <label class="checkbox-custom-label" for="DomainNameAPI_adp">
                        <span class="kinfo"><?php echo $LANG["desc"]["adp"]; ?></span>
                    </label>
                </div>
            </div>

            <div class="formcon" id="cost_currency_wrap">
                <div class="yuzde30"><?php echo $LANG["fields"]["cost-currency"]; ?></div>
                <div class="yuzde70">
                    <select name="cost-currency" style="width:200px;">
                        <?php
                        foreach (Money::getCurrencies($CONFIG["settings"]["cost-currency"]) as $currency) {
                            ?>
                            <option<?php echo $currency["id"] == $CONFIG["settings"]["cost-currency"] ? ' selected' : ''; ?> value="<?php echo $currency["id"]; ?>"><?php echo $currency["name"] . " (" . $currency["code"] . ")"; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="formcon">
                <div class="yuzde30"><?php echo __("admin/products/profit-rate-for-registrar-module"); ?></div>
                <div class="yuzde70">
                    <input type="text" name="profit-rate" value="<?php echo Config::get("options/domain-profit-rate"); ?>" style="width: 50px;" onkeypress='return event.charCode==44 || event.charCode==46 || event.charCode>= 48 &&event.charCode<= 57'>
                </div>
            </div>



            <div class="formcon">
                <div class="yuzde30"><?php echo $LANG["fields"]["balance"]; ?></div>
                <div class="yuzde70 js-balance" >

                </div>
            </div>


            <div class="formcon">
                <div class="yuzde30"></div>
                <div class="yuzde70 connection-time" >
                </div>
            </div>

            <div class="formcon">
                <div class="yuzde30"></div>
                <div class="yuzde70 version-check" >
                </div>
            </div>


            <div class="formcon" <?php if (!isset($balance['balances'])) { echo "style='display:none'"; } ?> >
                <div class="yuzde30"><?php echo $LANG["fields"]["importTld"]; ?></div>
                <div class="yuzde70">
                    <a class="lbtn" href="javascript:open_modal('DomainNameAPI_import_tld');void 0;"><?php echo $LANG["importTldButton"]; ?></a>
                    <div class="clear"></div>
                    <span class="kinfo"><?php echo $LANG["desc"]["importTld-1"]; ?></span>
                </div>
            </div>

            <div class="formcon" style="display: none;">
                <div class="yuzde30"><?php echo $LANG["fields"]["test-mode"]; ?></div>
                <div class="yuzde70">
                    <input<?php echo $CONFIG["settings"]["test-mode"] ? ' checked' : ''; ?> type="checkbox" name="test-mode" value="1" id="DomainNameAPI_test-mode" class="checkbox-custom">
                    <label class="checkbox-custom-label" for="DomainNameAPI_test-mode">
                        <span class="kinfo"><?php echo $LANG["desc"]["test-mode"]; ?></span>
                    </label>
                </div>
            </div>

            <div class="clear"></div>
            <br>

            <div style="float:left;" class="guncellebtn yuzde30">
                <a id="DomainNameAPI_testConnect" href="javascript:void(0);" class="lbtn">
                    <i class="fa fa-plug" aria-hidden="true"></i>
                    <?php echo $LANG["testButton"]; ?></a></div>

            <div style="float:right;" class="guncellebtn yuzde30">
                <a id="DomainNameAPI_submit" href="javascript:void(0);" class="yesilbtn gonderbtn"><?php echo $LANG["saveButton"]; ?></a>
            </div>

        </form>
        <script type="text/javascript">
          $(document).ready(function() {
            $('#DomainNameAPI_testConnect').click(function() {
              $('#DomainNameAPISettings input[name=controller]').val('test_connection');
              MioAjaxElement($(this), {
                waiting_text : waiting_text,
                progress_text: progress_text,
                result       : 'DomainNameAPI_handler',
              });
            });

            $('#DomainNameAPI_submit').click(function() {
              $('#DomainNameAPISettings input[name=controller]').val('settings');
              MioAjaxElement($(this), {
                waiting_text : waiting_text,
                progress_text: progress_text,
                result       : 'DomainNameAPI_handler',
              });
            });
          });

          function DomainNameAPI_handler(result) {
            if (result != '') {
              var solve = getJson(result);
              if (solve !== false) {
                if (solve.status == 'error') {
                  if (solve.for != undefined && solve.for != '') {
                    $('#DomainNameAPISettings ' + solve.for).focus();
                    $('#DomainNameAPISettings ' + solve.for).attr('style', 'border-bottom:2px solid red; color:red;');
                    $('#DomainNameAPISettings ' + solve.for).change(function() {
                      $(this).removeAttr('style');
                    });
                  }
                  if (solve.message != undefined && solve.message != '') {
                    alert_error(solve.message, {timer: 5000});
                  }
                }
                else if (solve.status == 'successful') {
                  alert_success(solve.message, {timer: 2500});
                }
              }
              else {
                console.log(result);
              }
            }
          }
        </script>

    </div>

    <div id="dna-tab-import" class="modules-tabs-content" style="display: none;">

        <div class="blue-info">
            <div class="padding15">
                <?php echo $LANG["importNote"]; ?>
            </div>
        </div>


        <form action="<?php echo Controllers::$init->getData("links")["controller"]; ?>" method="post" id="DomainNameAPIImport">
            <input type="hidden" name="operation" value="module_controller">
            <input type="hidden" name="module" value="DomainNameAPI">
            <input type="hidden" name="controller" value="import">

            <table width="100%" id="dna-list-domains" class="table table-striped table-borderedx table-condensed nowrap">
                <thead style="background:#ebebeb;">
                <tr>
                    <th align="center" data-orderable="false">#</th>
                    <th align="left" data-orderable="false"><?php echo __("admin/products/hosting-shared-servers-import-accounts-domain"); ?></th>
                    <th align="center" data-orderable="false"><?php echo __("admin/products/hosting-shared-servers-import-accounts-user"); ?></th>
                    <th align="center" data-orderable="false"><?php echo __("admin/products/hosting-shared-servers-import-accounts-start"); ?></th>
                    <th align="center" data-orderable="false"><?php echo __("admin/products/hosting-shared-servers-import-accounts-end"); ?></th>
                </tr>
                </thead>
                <tbody align="center" style="border-top:none;">

                </tbody>
            </table>

            <hr style="margin-top: 30px">

            <h4>
                <?php echo $LANG["headerImport"]; ?>
            </h4>
            <table width="100%" id="dna-list-queue" class="table table-striped table-borderedx table-condensed nowrap">
                <thead style="background:#ebebeb;">
                <tr>
                    <th align="center" data-orderable="false">#</th>
                    <th align="left" data-orderable="false"><?php echo __("admin/products/hosting-shared-servers-import-accounts-domain"); ?></th>
                    <th align="center" data-orderable="false"><?php echo __("admin/products/hosting-shared-servers-import-accounts-user"); ?></th>
                    <th align="center" data-orderable="false"></th>
                </tr>
                </thead>
                <tbody align="center" style="border-top:none;">
                </tbody>
            </table>

            <div class="clear"></div>
            <div class="guncellebtn yuzde20" style="float: right;">
                <a href="javascript:void(0);" id="dna-import-submit" class="gonderbtn mavibtn"><?php echo $LANG["importStartButton"]; ?></a>
            </div>

        </form>

    </div>

    <div id="dna-tab-tlds" class="modules-tabs-content" style="display: none;">

        <div class="blue-info">
            <div class="padding15">
                <?php echo $LANG["importTldNote"]; ?>
            </div>
        </div>


        <form action="<?php echo Controllers::$init->getData("links")["controller"]; ?>" method="post" id="DomainNameAPITLDImport">
            <input type="hidden" name="operation" value="module_controller">
            <input type="hidden" name="module" value="DomainNameAPI">
            <input type="hidden" name="controller" value="tldImport">

            <table width="100%" id="dna-list-tlds" class="table table-striped table-borderedx table-condensed nowrap">
                <thead style="background:#ebebeb;">
                <tr>
                    <th align="center" data-orderable="false" style="background: unset;">#</th>
                    <th align="left" data-orderable="false" style="background: unset;"> </th>
                    <th align="left" data-orderable="false" style="background: unset;"> </th>
                    <th align="left" data-orderable="false" style="background: unset;"> </th>
                    <th align="center" data-orderable="false" colspan="3" class="dt-head-1-spans"><?php echo $LANG['register'];?></th>
                    <th align="center" data-orderable="false" colspan="3" class="dt-head-1-spans"><?php echo $LANG['transfer'];?></th>
                    <th align="center" data-orderable="false" colspan="3" class="dt-head-1-spans"><?php echo $LANG['renew'];?></th>
                     <th align="left" data-orderable="false" style="background: unset;"> </th>
                </tr>
                <tr>
                    <th align="center" data-orderable="false"></th>
                    <th align="center" data-orderable="false">
                        <input type="checkbox" id="checkAll">
                    </th>
                    <th align="left" data-orderable="false"><?php echo $LANG['tld'];?></th>
                    <th align="left" data-orderable="false"><?php echo $LANG['dna'];?></th>
                    <th align="center" data-orderable="false" class="dt-head-2-spans"><?php echo $LANG['cost'];?></th>
                    <th align="center" data-orderable="false" class="dt-head-2-spans"><?php echo $LANG['current'];?></th>
                    <th align="center" data-orderable="false" class="dt-head-2-spans"><?php echo $LANG['margin'];?></th>
                    <th align="center" data-orderable="false" class="dt-head-2-spans"><?php echo $LANG['cost'];?></th>
                    <th align="center" data-orderable="false" class="dt-head-2-spans"><?php echo $LANG['current'];?></th>
                    <th align="center" data-orderable="false" class="dt-head-2-spans"><?php echo $LANG['margin'];?></th>
                    <th align="center" data-orderable="false" class="dt-head-2-spans"><?php echo $LANG['cost'];?></th>
                    <th align="center" data-orderable="false" class="dt-head-2-spans"><?php echo $LANG['current'];?></th>
                    <th align="center" data-orderable="false" class="dt-head-2-spans"><?php echo $LANG['margin'];?></th>
                    <th align="left" data-orderable="false" class="dt-head-2-min">Excl.
                        <a href="javascript:void(0)" class="sbtn excl-save-btn" style="display: none;"><i class="fa fa-floppy-o"></i></a>
                    </th>

                </tr>
                </thead>
                <tbody align="center" style="border-top:none;"></tbody>
            </table>


            <div class="clear"></div>
            <div class="guncellebtn yuzde20" style="float: left;">
                <a href="javascript:void(0);" id="dna-tld-import-submit" class="gonderbtn mavibtn"><?php echo $LANG["importStartButton"]; ?><span></span></a>
            </div>

        </form>



    </div>

</div>

<div id="DomainNameAPI_import_tld" style="display: none;" data-izimodal-title="<?php echo $LANG["fields"]["importTld"]; ?>">
    <div class="padding20">

        <p style="text-align: center; font-size: 17px;">
            <?php echo $LANG["desc"]["importTld-2"]; ?>
        </p>

        <div align="center">
            <div class="yuzde50">
                <a class="yesilbtn gonderbtn" href="javascript:void 0;" id="DomainNameAPI_import_tld_submit">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    <?php echo ___("needs/ok"); ?></a>
            </div>
        </div>


    </div>
</div>



<script type="text/javascript">


  function DomainNameAPI_import_handler2(result){
    console.log(result);
    console.log(getJson(result));
  }

  function DomainNameAPI_import_handler(result) {

    if (result != '') {
      var solve = getJson(result);
      if (solve !== false) {
        if (solve.status == 'error') {
          if (solve.for != undefined && solve.for != '') {
            $('#DomainNameAPIImport ' + solve.for).focus();
            $('#DomainNameAPIImport ' + solve.for).attr('style', 'border-bottom:2px solid red; color:red;');
            $('#DomainNameAPIImport ' + solve.for).change(function() {
              $(this).removeAttr('style');
            });
          }
          if (solve.message != undefined && solve.message != '') {
            alert_error(solve.message, {timer: 5000});
          }
        }
        else if (solve.status == 'successful') {
          alert_success(solve.message, {timer: 2500});
          setTimeout(function() {
            window.location.href = window.location.href;
          }, 2500);
        }
      }
      else {
        console.log(result);
      }
    }

  }

  const versionCheckUrl = "https://api.github.com/repos/domainreseller/wisecp-dna/releases/latest";
  const requestUrl = "<?php echo Controllers::$init->getData('links')['controller']; ?>";
  const domainsListRequest = {
    operation : 'module_controller',
    module    : 'DomainNameAPI',
    controller: 'domainlist',
  };
  const tldsListRequest = {
    operation : 'module_controller',
    module    : 'DomainNameAPI',
    controller: 'tldlist',
  };
  const settingsRequest = {
    operation : 'module_controller',
    module    : 'DomainNameAPI',
    controller: 'settings',
  };
  const importTldRequest = {
    operation : 'module_controller',
    module    : 'DomainNameAPI',
    controller: 'import-tld',
  };
  const userInfoRequest = {
    operation : 'module_controller',
    module    : 'DomainNameAPI',
    controller: 'userinfo',
  };

  const languageUrl = "<?php echo APP_URI; ?>/<?php echo ___("package/code"); ?>/datatable/lang.json";
  const allText = "<?php echo ___("needs/allOf"); ?>";
  const userUrl = "<?php echo Controllers::$init->AdminCRLink("users-2", ['detail', ':uid:']);?>";
  const select2Placeholder = "<?php echo __("admin/invoices/create-select-user"); ?>";
  const select2SearchUrl = "<?php echo Controllers::$init->AdminCRLink("orders"); ?>?operation=user-list.json";
  const noImportDomainsMessage = "<?php echo $LANG['noImportDomains']; ?>";
  const importQuestionMessage = "<?php echo $LANG['importQuestion']; ?>";
  const yesMessage = "<?php echo $LANG['yes']; ?>";
  const noMessage = "<?php echo $LANG['no']; ?>";
  const importProcessingMessage = "<?php echo $LANG['importProcessing']; ?>";
  const processMessage = "<?php echo $LANG['process']; ?>";
  const importFinishedMessage = "<?php echo $LANG['importFinished']; ?>";
  const okMessage = "<?php echo $LANG['okey']; ?>";
  const tldMessage = "<?php echo $LANG['tld'];?>";
  const dnaMessage = "<?php echo $LANG['dna'];?>";
  const costMessage = "<?php echo $LANG['cost'];?>";
  const currentMessage = "<?php echo $LANG['current'];?>";
  const marginMessage = "<?php echo $LANG['margin'];?>";
  const registerMessage = "<?php echo $LANG['register'];?>";
  const renewMessage = "<?php echo $LANG['renew'];?>";
  const transferMessage = "<?php echo $LANG['transfer'];?>";
  const noTldSelectedMessage = "<?php echo $LANG['noTldSelected'];?>";
  const noTldSelectedDescMessage = "<?php echo $LANG['noTldSelectedDesc'];?>";
  const numofTLDSelectedMessage = "<?php echo $LANG['numofTLDSelected'];?>";
  const numofTLDSyncedMessage = "<?php echo $LANG['numofTLDSynced'];?>";
  const numofTLDSyncedTxtMessage = "<?php echo $LANG['numofTLDSyncedTxt'];?>";
  const numofTLDNotSyncedMessage = "<?php echo $LANG['numofTLDNotSynced'];?>";
  const numofTLDNotSyncedTxtMessage = "<?php echo $LANG['numofTLDNotSyncedTxt'];?>";
  const stillProcessingMessage = "<?php echo $LANG['stillProcessing'];?>";
  const expectedProfitRate = <?php echo Config::get('options/domain-profit-rate') * 1; ?>;
  const currentVersion = '<?php echo $module->version; ?>';
  const txtVersion1 = '<?php echo $LANG['version1']; ?>';
  const txtVersion2 = '<?php echo $LANG['version2']; ?>';
  const txtVersion3 = '<?php echo $LANG['version3']; ?>';
  const txtVersion4 = '<?php echo $LANG['version4']; ?>';
  const eplasedTime = '<?php echo $LANG['eplasedTime']; ?>';

  let queueTable;
  let tldTable;
  let importTabInit = false;
  let importTldTabInit = false;
  let originalState = {};
  let lastChecked = null;

  function initializeImportTab() {
    if (importTabInit) return;
    importTabInit = true;

    queueTable = $('#dna-list-queue').DataTable({
      columnDefs: [{'targets': [0], 'visible': false}],
      lengthMenu: [[10, 25, 50, -1], [10, 25, 50, allText]],
      responsive: true,
      language  : {'url': languageUrl},
    });

    $('#dna-list-domains').DataTable({
      search      : false,
      processing  : true,
      serverSide  : true,
      ajax        : {
        url: requestUrl,
        type: 'POST',
        data: (d) => $.extend({}, d, domainsListRequest),
      },
      columns     : [
        {
          'data': null, 'render': function(data, type, row, meta) {
            return meta.row + 1;
          },
        },
        {'data': 'domain'},
        {
          'data': null, 'render': function(data, type, row) {
            if (row.user_data.id) {
              let user_link = userUrl.replace(':uid:', row.user_data.id);
              let user_name = row.user_data.full_name ? (row.user_data.full_name.length > 21
                  ? row.user_data.full_name.substring(0, 21) + '...'
                  : row.user_data.full_name) : '';
              let company_name = row.user_data.company_name ? (row.user_data.company_name.length > 21
                  ? row.user_data.company_name.substring(0, 21) + '...'
                  : row.user_data.company_name) : '';
              return `<a href="${user_link}" target="_blank"><strong title="${row.user_data.full_name}">${user_name}</strong></a><br><span class="mobcomname" title="${row.user_data.company_name}">${company_name}</span>`;
            }
            else {
              return '<select class="width200 select-user-dna" name="data[' + row.domain + '][user_id]"></select><span class="sbtn-container"></span>';
            }
          },
        },
        { 'data': 'creation_date' },
        { 'data': 'end_date' },
      ],
      lengthMenu  : [
        [50, 10, 25, -1], [50, 10, 25, allText],
      ],
      responsive  : true,
      language    : {'url': languageUrl},
      rowCallback : function(row, data, index) {
        if (data.order_id > 0) {
          $(row).css({ 'background-color': '#c2edc2', 'opacity': '0.7', 'filter': 'alpha(opacity=70)' });
        }
      },
      drawCallback: function(settings) {
        $('.select-user-dna').select2({
          placeholder: select2Placeholder,
          ajax       : {
            url     : select2SearchUrl,
            dataType: 'json',
            data    : function(params) {
              return {
                search: params.term,
                type  : 'public',
              };
            },
          },
        }).on('select2:select', function(e) {

          let data = e.params.data;
          let _select = $(this);
          let domain = _select.closest('tr').find('td').eq(1).text(); // Domain name
          let userId = data.id; // Selected user ID
          let userName = data.text; // Selected user name

          // Eğer aynı domain zaten eklenmişse, mevcut satırı çıkar
          queueTable.rows().every(function() {
            const row = this.data();
            if (row[0] === domain) {
              this.remove().draw();
              return false;
            }
          });

          const rowNode = queueTable.row.add([
            domain,
            `<span>${domain}</span><input type="hidden" name="domain_id" value="${domain}">`,
            `<span>${userName}</span><input type="hidden" name="user_id" value="${userId}">`,
            `<button type="button" class="sbtn remove-row"><i class="fa fa-trash"></i></button>`,
          ]).draw().node();

          $(rowNode).find('.remove-row').on('click', function() {
            queueTable.row($(this).closest('tr')).remove().draw();
          });

        });
      },
    });

    $('.select2-element').select2({
      placeholder: "<?php echo ___('needs/select-your'); ?>",
    });
  }

  function initializeTldImportTab(){


    if(importTldTabInit) return;
    importTldTabInit = true;

    try{
      tldTable.columns.adjust().draw();
    }catch (e) {
      console.log(e);
    }

    tldTable=$('#dna-list-tlds').DataTable({
      search: false,
      processing  : true,
      serverSide  : true,
      ajax        : {
        url: requestUrl,
        type: 'POST',
        data: (d) => $.extend({}, d, tldsListRequest),
      },
        columns: [
            {'data': null, 'render': function(data, type, row, meta) {return meta.row + 1;}},
            {'data': null, 'render': function(data, type, row, meta) {return `<input type="checkbox" name="tld[]" value="${row.tld}" class="tld-checkbox">`;}},
            {'data': 'tld'},
            {'data': null, 'render': function(data, type, row, meta) {return renderModuleIcon(row.module);}},
            {'data': null, 'render': function(data, type, row, meta) {return renderCost(row.register_cost);}},
            {'data': null, 'render': function(data, type, row, meta) {return renderCost(row.register_current);}},
            {'data': null, 'render': function(data, type, row, meta) {return renderMarginPercent(row.register_margin_percent, expectedProfitRate);}},
            {'data': null, 'render': function(data, type, row, meta) {return renderCost(row.transfer_cost);}},
            {'data': null, 'render': function(data, type, row, meta) {return renderCost(row.transfer_current);}},
            {'data': null, 'render': function(data, type, row, meta) {return renderMarginPercent(row.transfer_margin_percent, expectedProfitRate);}},
            {'data': null, 'render': function(data, type, row, meta) {return renderCost(row.renewal_cost);}},
            {'data': null, 'render': function(data, type, row, meta) {return renderCost(row.renewal_current);}},
            {'data': null, 'render': function(data, type, row, meta) {return renderMarginPercent(row.renewal_margin_percent, expectedProfitRate);}},
            {'data': null, 'render': function(data, type, row, meta) {return renderExcludedTldCb(row);}}

        ],
      columnDefs: [{ 'targets': [0], 'visible': false }],
      lengthMenu: [[-1], [allText]],
      responsive: true,
      language  : {'url': languageUrl},
      scrollY: '400px',
      scrollCollapse: true,
      initComplete: function() {
          saveOriginalState();
        }

    });




  }

  $(document).ready(function() {

    setTimeout(function() {
       $('.mod-show-ready').show();
    }, 1700);


    setTimeout(function() {
       getBalanceInfo();
       checkModuleVersion();
    }, 500);

    $('#DomainNameAPI_import_tld_submit').on('click', function() {
      var request = MioAjax({
        button_element: this,
        action        : "<?php echo Controllers::$init->getData("links")["controller"]; ?>",
        method        : 'POST',
        waiting_text  : waiting_text,
        progress_text : progress_text,
        data          : {
          operation : 'module_controller',
          module    : 'DomainNameAPI',
          controller: 'importTld',
        },
      }, true, true);

      request.done(function(result) {
        if (result != '') {
          var solve = getJson(result);
          if (solve !== false) {
            if (solve.status == 'error') {
              if (solve.for != undefined && solve.for != '') {
                $('#detailForm ' + solve.for).focus();
                $('#detailForm ' + solve.for).attr('style', 'border-bottom:2px solid red; color:red;');
                $('#detailForm ' + solve.for).change(function() {
                  $(this).removeAttr('style');
                });
              }
              if (solve.message != undefined && solve.message != '') {
                alert_error(solve.message, {timer: 5000});
              }
            }
            else if (solve.status == 'successful') {
              alert_success(solve.message, {timer: 2000});
              if (solve.redirect != undefined && solve.redirect != '') {
                setTimeout(function() {
                  window.location.href = solve.redirect;
                }, 2000);
              }
            }
          }
          else {
            console.log(result);
          }
        }
      });

    });

    $('#checkAll').on('change', function() {
      const rows = $('#dna-list-tlds').find('tbody tr');
      rows.each(function() {
        const checkbox = $(this).find('.tld-checkbox');
        checkbox.prop('checked', !checkbox.prop('checked'));
      });
    });

    $(document).on('click', '.tld-checkbox', function(e) {
      if (!lastChecked) {
        lastChecked = this;
        return;
      }

      if (e.shiftKey) {
        const start = $('.tld-checkbox').index(this);
        const end = $('.tld-checkbox').index(lastChecked);
        const checkboxes = $('.tld-checkbox').slice(Math.min(start, end), Math.max(start, end) + 1);
        checkboxes.prop('checked', lastChecked.checked);
      }

      lastChecked = this;
    });

    $(document).on('change', '.tld-checkbox', function() {
      const count = $('.tld-checkbox:checked').length;
      const txt = count > 0 ? `(${count}) ` : '';
      $('#dna-tld-import-submit span').text(txt);
    });

    $(document).on('change', '.excl-checkbox', toggleSaveButton);

    $(document).on('click', '.excl-save-btn', function() {
      $(this).hide();
      const _data = $.extend({}, settingsRequest, {
        exclude: $('.excl-checkbox:checked').map(function() {
          return $(this).val();
        }).get().join(','),
      });

      $.ajax({
        url    : requestUrl,
        method : 'POST',
        data   : _data,
        success: function(response) {
          saveOriginalState();
          toggleSaveButton();
        },
        error  : function(error) {
          console.log('Error:', error);
        },
      });
    });

    $('#dna-import-submit').on('click', function() {
      const queueLength = queueTable.rows().data().length;
    if (queueLength === 0) {
      swal({
        icon : 'warning',
        title: 'Uyarı',
        text : noImportDomainsMessage,
      });
      return;
    }

    swal({
      title            : `${queueLength} ${importQuestionMessage}`,
      showCancelButton : true,
      confirmButtonText: yesMessage,
      cancelButtonText : noMessage,
    }).then((result) => {

      let queueData = queueTable.rows().data().toArray();
      let currentIndex = 0;

      const processNext = () => {
        if (currentIndex < queueData.length) {
          let rowData = queueData[currentIndex];
          console.log(rowData);
          let domain = rowData[0];

          let $userField = $('<div>').html(rowData[2]);
          let userId = $userField.find('input[name="user_id"]').val();
          let userName = $userField.find('span').text();

          let postData = $.extend({}, domainsListRequest,
              {domain: domain, user_id: userId, controller: 'import-single'});

          // Yeni bir swal oluştur ve progress barı güncelle
          swal({
            title            : importProcessingMessage,
            html             :
                '<div  style="display: inline-flex;">' + processMessage + (currentIndex + 1) + '/' +
                (queueData.length) + ' <div class="loader" style="scale: 0.4"></div>  </div>' +
                '<div > ' + domain + ' → ' + userName + '</div>',
            allowOutsideClick: false,
            showConfirmButton: false,
          });

          $.post(requestUrl, postData, function(response) {
            currentIndex++;

            // Sonraki domaini işleme al
            processNext();
          });
        }
        else {
          // İşlem tamamlandı

          let request_invalidate = domainsListRequest;
          request_invalidate.start = 0;
          request_invalidate.length = 50;
          request_invalidate.invalidate = 1;

          $.post(requestUrl, request_invalidate, function(response) {});

          swal.close();
          swal({
            icon             : 'success',
            title            : importFinishedMessage,
            confirmButtonText: okMessage,
          }).then(() => {
            queueTable.clear().draw();
            $('#dna-list-domains').DataTable().ajax.reload();
          });
        }
      };

      // İlk domaini işleme al
      processNext();

    });
  });

    $('#dna-tld-import-submit').on('click', function() {
      const selectedTlds = $('.tld-checkbox:checked').map(function() {
        return $(this).val();
      }).get();

      const tldCount = selectedTlds.length;

      if (tldCount === 0) {
        swal({
          icon : 'warning',
          title: noTldSelectedMessage,
          text : noTldSelectedDescMessage,
        });
        return;
      }

      swal({
        title            : `${tldCount} ${numofTLDSelectedMessage}`,
        showCancelButton : true,
        confirmButtonText: yesMessage,
        cancelButtonText : noMessage,
      }).then((result) => {

          swal({
            title            : stillProcessingMessage,
            html             : '<div style="display: inline-flex;"> '+stillProcessingMessage+' <div class="loader" style="scale: 0.4"></div>  </div>',
            allowOutsideClick: false,
            showConfirmButton: false,
          });

          const postData = $.extend({}, importTldRequest, {onlytlds: selectedTlds});

          $.ajax({
            url    : requestUrl,
            method : 'post',
            data   : postData,
            success: function(response) {
              swal({
                icon : 'success',
                title: `${tldCount} ${numofTLDSyncedMessage}`,
                text : numofTLDSyncedTxtMessage,
              });
            },
            error  : function(error) {
              swal({
                icon : 'error',
                title: numofTLDNotSyncedMessage,
                text : numofTLDNotSyncedTxtMessage,
              });
            },
          });

      });
    });

  });

  function getBalanceInfo() {

    $('.js-balance').html('<div class="loader" style="scale: 0.4"></div>');

    const startTime = performance.now();

    $.post(requestUrl, userInfoRequest, function(response) {
      const endTime = performance.now();
      const responseTime = ((endTime - startTime) / 1000).toFixed(2);

      let style_attr = response.loggedin === true
          ? 'color: #4CAF50;font-weight:bold'
          : 'color: #F44336;font-weight:bold';
      let icon = response.loggedin === true
          ? '<i class="fa fa-check"></i>'
          : '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>';

      $('.js-balance').attr('style', style_attr);
      $('.js-balance').html('<span>' + icon + ' ' + response.message + '</span>');

      let timeColor = responseTime < 1 ? 'green' : responseTime < 3 ? 'orange' : 'red';
      $('.connection-time').html('<span style="color:' + timeColor + ';">' + eplasedTime.replace(':time:', responseTime) + '</span>');
    }, 'json');
  }

  function processQueue(queueLength) {
      const queueData = queueTable.rows().data().toArray();
      let currentIndex = 0;

      const processNext = () => {
        if (currentIndex < queueData.length) {
          const rowData = queueData[currentIndex];
          const domain = rowData[0];
          const userField = $('<div>').html(rowData[2]);
          const userId = userField.find('input[name="user_id"]').val();
          const userName = userField.find('span').text();

          const postData = $.extend({}, domainsListRequest, {
            domain: domain,
            user_id: userId,
            controller: 'import-single'
          });

          swal({
            title: importProcessingMessage,
            html: `<div style="display: inline-flex;">${processMessage} ${currentIndex + 1}/${queueLength} <div class="loader" style="scale: 0.4"></div></div><div>${domain} → ${userName}</div>`,
            allowOutsideClick: false,
            showConfirmButton: false,
          });

          $.post(requestUrl, postData, function(response) {
            currentIndex++;
            processNext();
          });
        } else {
          swal.close();
          swal({
            icon: 'success',
            title: importFinishedMessage,
            confirmButtonText: okMessage,
          }).then(() => {
            queueTable.clear().draw();
            $('#dna-list-domains').DataTable().ajax.reload();
  });
        }
      };

      processNext();
    }

  function DNAOpenTab(elem, tabName) {
    const owner = 'dna-tab';
    $('#' + owner + ' .modules-tabs-content').hide();
    $('#' + owner + ' .modules-tabs .modules-tab-item').removeClass('active');
    $('#' + owner + '-' + tabName).show();
    $('#' + owner + ' .modules-tabs .modules-tab-item[data-tab=\'' + tabName + '\']').addClass('active');

    if (tabName === 'import') {
      initializeImportTab();
    } else if (tabName === 'tlds') {
      initializeTldImportTab();
    }
  }

  function renderMarginPercent(marginPercent, expectedRate) {
    if (marginPercent !== null) {
      if (marginPercent === expectedRate) {
        return `<span style="color:#100d75;"><i class="fa fa-check"></i>${marginPercent}</span>`;
      }
      else if (marginPercent < expectedRate) {
        return `<span style="color:#ea1561;"><i class="fa fa-arrow-down"></i>${marginPercent}</span>`;
      }
      else if (marginPercent > expectedRate) {
        return `<span style="color:green;"><i class="fa fa-arrow-up"></i>${marginPercent}</span>`;
      }
    }
    return '-';
  }

  function renderCost(cost) {
    return cost !== null && cost > 0.01 ? cost : '-';
  }

  function renderModuleIcon(module) {
    return module === 'DomainNameAPI' ? '<i class="fa fa-thumbs-up"></i>' : '<i class="fa fa-thumbs-down"></i>';
  }

  function renderExcludedTldCb(row) {
    const checked = row.excluded ? 'checked' : '';
    return `<input type="checkbox" name="excl[]" value="${row.tld}" ${checked} class="excl-checkbox">`;
  }

  function saveOriginalState() {
    originalState = {};
    $('.excl-checkbox').each(function() {
      originalState[$(this).attr('name')] = $(this).is(':checked');
    });
  }

  function checkChanges() {
    let hasChanges = false;
    $('.excl-checkbox').each(function() {
      if (originalState[$(this).attr('name')] !== $(this).is(':checked')) {
        hasChanges = true;
        return false;
      }
    });
    return hasChanges;
  }

  function toggleSaveButton() {
    if (checkChanges()) {
      $('.excl-save-btn').show();
    }
    else {
      $('.excl-save-btn').hide();
    }
  }

  function checkModuleVersion() {

    $('.version-check').html('<div class="loader" style="scale: 0.4"></div>');

    $.get(versionCheckUrl, function(response) {

      const latestVersion = response.tag_name;
      const latestUrl = response.html_url;
      let versionText = '';

      if ('V' + currentVersion !== latestVersion) {
            versionText = `
              <p class="out-of-date"><i class="fas fa-minus-circle"></i> ${txtVersion1} <strong>V${currentVersion}</strong>.
              <br>Sunucudaki son versiyon: <strong>${latestVersion}</strong> ${txtVersion2}</p>
              <a href="${latestUrl}" target="_blank">${txtVersion3}</a>`;
          } else {
            versionText = `
              <p class="up-to-date"><i class="fas fa-check-circle"></i> ${txtVersion4} (V${currentVersion})</p>`;
          }

      $('.version-check').html(versionText);

    }, 'json');
  }

</script>

<style>
    .loader {
      width: 20px;
      aspect-ratio: 1;
      border-radius: 50%;
      border: 8px solid #514b82;
      animation:
        l20-1 0.8s infinite linear alternate,
        l20-2 1.6s infinite linear;
    }
    @keyframes l20-1{
       0%    {clip-path: polygon(50% 50%,0       0,  50%   0%,  50%    0%, 50%    0%, 50%    0%, 50%    0% )}
       12.5% {clip-path: polygon(50% 50%,0       0,  50%   0%,  100%   0%, 100%   0%, 100%   0%, 100%   0% )}
       25%   {clip-path: polygon(50% 50%,0       0,  50%   0%,  100%   0%, 100% 100%, 100% 100%, 100% 100% )}
       50%   {clip-path: polygon(50% 50%,0       0,  50%   0%,  100%   0%, 100% 100%, 50%  100%, 0%   100% )}
       62.5% {clip-path: polygon(50% 50%,100%    0, 100%   0%,  100%   0%, 100% 100%, 50%  100%, 0%   100% )}
       75%   {clip-path: polygon(50% 50%,100% 100%, 100% 100%,  100% 100%, 100% 100%, 50%  100%, 0%   100% )}
       100%  {clip-path: polygon(50% 50%,50%  100%,  50% 100%,   50% 100%,  50% 100%, 50%  100%, 0%   100% )}
    }
    @keyframes l20-2{
      0%    {transform:scaleY(1)  rotate(0deg)}
      49.99%{transform:scaleY(1)  rotate(135deg)}
      50%   {transform:scaleY(-1) rotate(0deg)}
      100%  {transform:scaleY(-1) rotate(-135deg)}
    }

    .dt-head-1-spans {
        border-right: 1px solid;
        border-left: 1px solid;
        border-top: 1px solid;
        text-align: center !important;
    }

    .dt-head-2-spans {
        /*
         border-right: 1px solid;
         border-left: 1px solid;
         border-top: 1px solid;
         text-align: center !important;
         border-bottom: 1px solid;
         */
    }

    th.dt-head-2-spans:nth-child(3n-2) {
        border-left: 1px solid #095174;
    }

    th.dt-head-2-spans:nth-child(3n) {
        border-right: 1px solid #095174;
    }

    #dna-list-tlds tbody tr td:nth-child(3), #dna-list-tlds tbody tr td:nth-child(4) {
        text-align: center;
    }
    #dna-list-tlds_length, #dna-list-tlds_filter,#dna-list-tlds_paginate {
        display: none;
    }
</style>
