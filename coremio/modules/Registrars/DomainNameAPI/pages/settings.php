<?php
if (!defined("CORE_FOLDER"))
    die();
$LANG   = $module->lang;
$CONFIG = $module->config;
Helper::Load("Money");
$is_soap = class_exists("SoapClient");
?>
<script type="text/javascript">
  function DomainNameAPI_open_tab(elem, tabName) {
    var owner = 'DomainNameAPI_tab';
    $('#' + owner + ' .modules-tabs-content').css('display', 'none');
    $('#' + owner + ' .modules-tabs .modules-tab-item').removeClass('active');
    $('#' + owner + '-' + tabName).css('display', 'block');
    $('#' + owner + ' .modules-tabs .modules-tab-item[data-tab=\'' + tabName + '\']').addClass('active');
  }
</script>

<div id="DomainNameAPI_tab">
    <ul class="modules-tabs">
        <li>
            <a href="javascript:DomainNameAPI_open_tab(this,'detail');" data-tab="detail" class="modules-tab-item active"><?php echo $LANG["tab-detail"]; ?></a>
        </li>
        <li>
            <a href="javascript:DomainNameAPI_open_tab(this,'import');" data-tab="import" class="modules-tab-item"><?php echo $LANG["tab-import"]; ?></a>
        </li>
    </ul>

    <div id="DomainNameAPI_tab-detail" class="modules-tabs-content" style="display: block">

        <?php
        if (!$is_soap) {
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

            <div class="formcon">
                <div class="yuzde30"><?php echo $LANG["fields"]["WHiddenAmount"]; ?></div>
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
                    <span class="kinfo"><?php echo $LANG["desc"]["WHiddenAmount"]; ?></span>
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

            <?php
            $balance       = $module->getDNABalance();
            $balance_total = 0;
            if (isset($balance['balances'])) {
                foreach ($balance['balances'] as $key => $value) {
                    $balance_total += $value["balance"];
                }
            }
            ?>

            <div class="formcon">
                <div class="yuzde30"><?php echo $LANG["fields"]["balance"]; ?></div>
                <div class="yuzde70" style="<?php echo $balance_total > 0 ? 'color: #4CAF50;font-weight:bold' : 'color: #F44336;font-weight:bold;'; ?>">
                    <?php
                    if (!isset($balance['balances'])) {
                        ?>
                        <div class="red-info"><span class="padding10"><?php echo $module->error; ?></span></div><?php
                    } else {

                        $balance_texts = [];

                        foreach ($balance['balances'] as $k => $v) {
                            if ($v["balance"] > 0) {
                                $balance_texts[] = $v["balance"] . " " . $v["currency"];
                            }
                        }
                        echo implode(", ", $balance_texts);
                    }
                    ?>

                    <div class="clear"></div>
                </div>
            </div>


            <div class="formcon">
                <div class="yuzde30"><?php echo $LANG["fields"]["import-tld"]; ?></div>
                <div class="yuzde70">
                    <a class="lbtn" href="javascript:open_modal('DomainNameAPI_import_tld');void 0;"><?php echo $LANG["import-tld-button"]; ?></a>
                    <div class="clear"></div>
                    <span class="kinfo"><?php echo $LANG["desc"]["import-tld-1"]; ?></span>
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
                    <?php echo $LANG["test-button"]; ?></a></div>

            <div style="float:right;" class="guncellebtn yuzde30">
                <a id="DomainNameAPI_submit" href="javascript:void(0);" class="yesilbtn gonderbtn"><?php echo $LANG["save-button"]; ?></a>
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

    <div id="DomainNameAPI_tab-import" class="modules-tabs-content" style="display: none;">

        <div class="blue-info">
            <div class="padding15">
                <?php echo $LANG["import-note"]; ?>
            </div>
        </div>

        <script type="text/javascript">

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

          $(document).ready(function() {

            $('#DomainNameAPI_import_submit').click(function() {
              MioAjaxElement($(this), {
                waiting_text : waiting_text,
                progress_text: progress_text,
                result       : 'DomainNameAPI_import_handler',
              });
            });

            $('#DomainNameAPI_list_domains').DataTable({
              'columnDefs': [
                {
                  'targets': [0],
                  'visible': false,
                },
              ],
              'lengthMenu': [
                [10, 25, 50, -1], [10, 25, 50, "<?php echo ___("needs/allOf"); ?>"],
              ],
              responsive  : true,
              'language'  : {'url': "<?php echo APP_URI; ?>/<?php echo ___("package/code"); ?>/datatable/lang.json"},
            });

            $('.select-user').select2({
              placeholder: "<?php echo __("admin/invoices/create-select-user"); ?>",
              ajax       : {
                url     : '<?php echo Controllers::$init->AdminCRLink("orders"); ?>?operation=user-list.json',
                dataType: 'json',
                data    : function(params) {
                  var query = {
                    search: params.term,
                    type  : 'public',
                  };
                  return query;
                },
              },
            });

            $('.select2-element').select2({
              placeholder: "<?php echo ___("needs/select-your"); ?>",
            });

          });
        </script>
        <form action="<?php echo Controllers::$init->getData("links")["controller"]; ?>" method="post" id="DomainNameAPIImport">
            <input type="hidden" name="operation" value="module_controller">
            <input type="hidden" name="module" value="DomainNameAPI">
            <input type="hidden" name="controller" value="import">

            <table width="100%" id="DomainNameAPI_list_domains" class="table table-striped table-borderedx table-condensed nowrap">
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
                <?php
                $list = $module->domains();
                $i    = 0;
                if ($list) {
                    foreach ($list as $row) {
                        $i++;
                        ?>
                        <tr<?php echo isset($row["order_id"]) && $row["order_id"] ? ' style="background: #c2edc2;opacity: 0.7;    filter: alpha(opacity=70);"' : ''; ?>>
                            <td align="left"><?php echo $i; ?></td>
                            <td align="left"><?php echo $row["domain"]; ?></td>
                            <td align="center">
                                <?php
                                if (isset($row["user_data"]) && $row["user_data"]) {
                                    $user_link         = Controllers::$init->AdminCRLink("users-2", [
                                        'detail',
                                        $row["user_data"]["id"]
                                    ]);
                                    $user_name         = Utility::short_text($row["user_data"]["full_name"], 0, 21, true);
                                    $user_company_name = Utility::short_text($row["user_data"]["company_name"], 0, 21, true);

                                    $user_detail = '<a href="' . $user_link . '" target="_blank"><strong title="' . $row["user_data"]["full_name"] . '">' . $user_name . '</strong></a><br><span class="mobcomname" title="' . $row["user_data"]["company_name"] . '">' . $user_company_name . '</span>';
                                    echo $user_detail;
                                } else {
                                    ?>
                                    <select class="width200 select-user" name="data[<?php echo $row["domain"]; ?>][user_id]"></select>
                                    <?php
                                }
                                ?>
                            </td>
                            <td align="center">
                                <?php echo $row["creation_date"] ? DateManager::format(Config::get("options/date-format"), $row["creation_date"]) : '-'; ?>
                            </td>
                            <td align="center">
                                <?php echo $row["end_date"] ? DateManager::format(Config::get("options/date-format"), $row["end_date"]) : '-'; ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
            </table>

            <div class="clear"></div>
            <div class="guncellebtn yuzde20" style="float: right;">
                <a href="javascript:void(0);" id="DomainNameAPI_import_submit" class="gonderbtn mavibtn"><?php echo $LANG["import-button"]; ?></a>
            </div>

        </form>

    </div>
</div>

<div id="DomainNameAPI_import_tld" style="display: none;" data-izimodal-title="<?php echo $LANG["fields"]["import-tld"]; ?>">
    <script type="text/javascript">
      $(document).ready(function() {

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
              controller: 'import-tld',
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

      });
    </script>
    <div class="padding20">

        <p style="text-align: center; font-size: 17px;">
            <?php echo $LANG["desc"]["import-tld-2"]; ?>
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
