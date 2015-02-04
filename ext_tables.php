<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}


if (TYPO3_MODE === 'BE') {
  
  $modulName = 'qucosaMain';
  //Legt die Position des Moduls fest, hier nach Modul "web"
  if (!isset($TBE_MODULES[$modulName])) {
    $temp_TBE_MODULES = array();
    foreach ($TBE_MODULES as $key => $val) {
      if ($key == 'file') {
        $temp_TBE_MODULES[$key] = $val;
        $temp_TBE_MODULES[$modulName] = '';
      } else {
        $temp_TBE_MODULES[$key] = $val;
      }
    }
    $TBE_MODULES = $temp_TBE_MODULES;
  }
  
  
  \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'Eww.' . $_EXTKEY,
        'qucosaMain',
        '',
        '',
        array(),       
        array(
          'access' => 'user,group',
          'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_qucosa_mod_main.xlf',
        )
  );
  
  \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
          'Eww.' . $_EXTKEY,
          'qucosaMain',   
          'qucosamanager',
          '',		
          array(
                  'Document' => 'list, show, new, create, edit, update, delete',
                  'DocumentFormBE' => 'list, show, new, create, edit, update, delete',
                  'AjaxDocumentForm' => 'group,fileGroup,field',
          ),
          array(
                  'access' => 'user,group',
                  'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
                  'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_qucosa_mod_manager.xlf',
          )
  );
}


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Qucosaform',
	'QucosaForm'
);


// qucosaform plugin configuration: additional fields
$extensionName = strtolower(\TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($_EXTKEY));
$pluginName = strtolower('Qucosaform');
$pluginSignature = $extensionName.'_'.$pluginName;

//$TCA['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:'.$_EXTKEY . '/Configuration/FlexForms/qucosaform_plugin.xml');
// end of qucosaform plugin configuration

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Qucosa Publication');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_dpf_domain_model_documenttype', 'EXT:dpf/Resources/Private/Language/locallang_csh_tx_dpf_domain_model_documenttype.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_dpf_domain_model_documenttype');
$GLOBALS['TCA']['tx_dpf_domain_model_documenttype'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:dpf/Resources/Private/Language/locallang_db.xlf:tx_dpf_domain_model_documenttype',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'name,display_name,metadata_page,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/DocumentType.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_dpf_domain_model_documenttype.gif'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_dpf_domain_model_document', 'EXT:dpf/Resources/Private/Language/locallang_csh_tx_dpf_domain_model_document.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_dpf_domain_model_document');
$GLOBALS['TCA']['tx_dpf_domain_model_document'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:dpf/Resources/Private/Language/locallang_db.xlf:tx_dpf_domain_model_document',
		'label' => 'xml_data',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'xml_data,document_type,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Document.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_dpf_domain_model_document.gif'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_dpf_domain_model_metadatagroup', 'EXT:dpf/Resources/Private/Language/locallang_csh_tx_dpf_domain_model_metadatagroup.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_dpf_domain_model_metadatagroup');
$GLOBALS['TCA']['tx_dpf_domain_model_metadatagroup'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:dpf/Resources/Private/Language/locallang_db.xlf:tx_dpf_domain_model_metadatagroup',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'name,display_name,mandatory,max_iteration,metadata_object,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/MetadataGroup.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_dpf_domain_model_metadatagroup.gif'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_dpf_domain_model_metadataobject', 'EXT:dpf/Resources/Private/Language/locallang_csh_tx_dpf_domain_model_metadataobject.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_dpf_domain_model_metadataobject');
$GLOBALS['TCA']['tx_dpf_domain_model_metadataobject'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:dpf/Resources/Private/Language/locallang_db.xlf:tx_dpf_domain_model_metadataobject',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'name,display_name,max_iteration,mandatory,mapping,input_field,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/MetadataObject.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_dpf_domain_model_metadataobject.gif'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_dpf_domain_model_documenttransfer', 'EXT:dpf/Resources/Private/Language/locallang_csh_tx_dpf_domain_model_documenttransfer.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_dpf_domain_model_documenttransfer');
$GLOBALS['TCA']['tx_dpf_domain_model_documenttransfer'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:dpf/Resources/Private/Language/locallang_db.xlf:tx_dpf_domain_model_documenttransfer',
		'label' => 'status',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'status,start_date,end_date,http_status,response,error,parent_document,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/DocumentTransfer.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_dpf_domain_model_documenttransfer.gif'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_dpf_domain_model_fedoraconnection', 'EXT:dpf/Resources/Private/Language/locallang_csh_tx_dpf_domain_model_fedoraconnection.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_dpf_domain_model_fedoraconnection');
$GLOBALS['TCA']['tx_dpf_domain_model_fedoraconnection'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:dpf/Resources/Private/Language/locallang_db.xlf:tx_dpf_domain_model_fedoraconnection',
		'label' => 'url',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'url,user,password,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/FedoraConnection.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_dpf_domain_model_fedoraconnection.gif'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_dpf_domain_model_metadatapage', 'EXT:dpf/Resources/Private/Language/locallang_csh_tx_dpf_domain_model_metadatapage.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_dpf_domain_model_metadatapage');
$GLOBALS['TCA']['tx_dpf_domain_model_metadatapage'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:dpf/Resources/Private/Language/locallang_db.xlf:tx_dpf_domain_model_metadatapage',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'name,display_name,page_number,metadata_group,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/MetadataPage.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_dpf_domain_model_metadatapage.gif'
	),
);
