<?php
namespace EWW\Dpf\Controller;


/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * DocumentFormController
 */
class DocumentFormController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * documentRepository
	 *
	 * @var \EWW\Dpf\Domain\Repository\DocumentRepository
	 * @inject
	 */
	protected $documentRepository = NULL;
        
        
        /**
	 * documentTypeRepository
	 *
	 * @var \EWW\Dpf\Domain\Repository\DocumentTypeRepository
	 * @inject
	 */
	protected $documentTypeRepository = NULL;        


        /**
	 * metadataGroupRepository
	 *
	 * @var \EWW\Dpf\Domain\Repository\MetadataGroupRepository
	 * @inject
	 */
	protected $metadataGroupRepository = NULL;

        
        /**
         * persistence manager
         *
         * @var \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
         * @inject
         */
        protected $persistenceManager;

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$documents = $this->documentRepository->findAll();
		$this->view->assign('documents', $documents);
	}

	/**
	 * action show
	 *
	 * @param \EWW\Dpf\Domain\Model\Document $document
	 * @return void
	 */
	public function showAction(\EWW\Dpf\Domain\Model\Document $document) {
                                  
		$this->view->assign('document', $document);
	}

	/**
	 * action new
	 *
	 * @param \EWW\Dpf\Domain\Model\Document $newDocument
	 * @ignorevalidation $newDocument
	 * @return void
	 */
	public function newAction(\EWW\Dpf\Domain\Model\Document $newDocument = NULL) {
          
                $documentType = $this->documentTypeRepository->findByUid(1);
                
                $document = $this->objectManager->create('\\EWW\\Dpf\\Domain\\Model\\Document');                              
               
                $mapper = new \EWW\Dpf\Helper\DocumentFormMapper();
                //$mapper->setDocument($document);
                $this->view->assign('documentForm', $mapper->getDocumentForm($documentType,$document));
            
	}

      
	/**
	 * action create
	 *
	 * @param array $newDocument
	 * @return void
	 */
	public function createAction(array $newDocument) {

                $documentType = $this->documentTypeRepository->findByUid($newDocument['type']);
                $newDoc = new \EWW\Dpf\Domain\Model\Document();
                $newDoc->setDocumentType($documentType);
                
                $mapper = new \EWW\Dpf\Helper\DocumentFormMapper();
                
                $newDocument = $mapper->getDocumentData($documentType,$newDocument);

                foreach ($newDocument['files'] as $tmpFile ) {
                                                      
                  $path = "uploads/tx_dpf";
                  $fileName = $path."/".time()."_".rand()."_".$tmpFile['name']; 
                  
                  if (\TYPO3\CMS\Core\Utility\GeneralUtility::upload_copy_move($tmpFile['tmp_name'],$fileName) ) {                    
                     $file['type'] = $mimeType = $tmpFile['type'];  
                     $file['path'] = $fileName;
                  
                     $files[] = $file;
                     
                  }
                                                                       
                }
                
                $newDocument['files'] = $files;
                
                
                $exporter = new \EWW\Dpf\Services\MetsExporter();                
                $exporter->buildModsFromForm($newDocument);
                
                $xml = $exporter->getMetsData();
	                        
                $newDoc->setXmlData($xml);                                                
                $this->documentRepository->add($newDoc);
                                
                $this->redirect('list');
	}

	/**
	 * action edit
	 *
	 * @param \EWW\Dpf\Domain\Model\Document $document
	 * @ignorevalidation $document
	 * @return void
	 */
	public function editAction(\EWW\Dpf\Domain\Model\Document $document) {                               
                $documentType = $document->getDocumentType();                
                $mapper = new \EWW\Dpf\Helper\DocumentFormMapper();               
                $documentForm = $mapper->getDocumentForm($documentType,$document);
		$this->view->assign('documentForm', $documentForm);                                                
	}

	/**
	 * action update
	 *
	 * @param array $updateDocument
	 * @return void
	 */
	public function updateAction(array $updateDocument) {
                   		
                $documentType = $this->documentTypeRepository->findByUid($updateDocument['type']);
                $document = $this->documentRepository->findByUid($updateDocument['documentUid']);  
                $document->setDocumentType($documentType);
                
                $mapper = new \EWW\Dpf\Helper\DocumentFormMapper();
               
                $updateDocument = $mapper->getDocumentData($documentType,$updateDocument);

                foreach ($updateDocument['files'] as $tmpFile ) {
                                                      
                  $path = "uploads/tx_dpf";
                  $fileName = $path."/".time()."_".rand()."_".$tmpFile['name']; 
                  
                  if (\TYPO3\CMS\Core\Utility\GeneralUtility::upload_copy_move($tmpFile['tmp_name'],$fileName) ) {                    
                     $file['type'] = $mimeType = $tmpFile['type'];  
                     $file['path'] = $fileName;
                  
                     $files[] = $file;
                     
                  }
                                                                       
                }
                
                $updateDocument['files'] = $files;
                
                
                $exporter = new \EWW\Dpf\Services\MetsExporter();                
                $exporter->buildModsFromForm($updateDocument);
                
                $xml = $exporter->getMetsData();
	                                                 
                $document->setXmlData($xml);                
                
                
                $this->documentRepository->update($document);
                                
		$this->redirect('list');
	}

	/**
	 * action delete
	 *
	 * @param \EWW\Dpf\Domain\Model\Document $document
	 * @return void
	 */
	public function deleteAction(\EWW\Dpf\Domain\Model\Document $document) {
		//$this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See <a href="http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain" target="_blank">Wiki</a>', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
		$this->documentRepository->remove($document);
		$this->redirect('list');
	}
        
                         
        /**
         *
         * @param integer $pageUid
         * @param integer $groupUid
         * @param integer $groupIndex
         * @return void
         */
        public function ajaxGroupAction(integer $pageUid, integer $groupUid, integer $groupIndex) {

           $group = $this->metadataGroupRepository->findByUid($groupUid);

           $groupItem = array();

           foreach ($group->getMetadataObject() as $object) {

                $field = array(                    
                    'uid' => $object->getUid(),
                    'displayName' => $object->getDisplayName(),
                    'mandatory' => $object->getMandatory(),
                    'inputField' => $object->getInputField(),
                    'items' => array(
                        0 => ""
                    )
                );

                $groupItem['fields'][] = $field;

           }
                              
           $this->view->assign('formPageUid',$pageUid);
           $this->view->assign('formGroupUid',$groupUid);
           $this->view->assign('formGroupDisplayName',$group->getDisplayName());
           $this->view->assign('groupIndex',$groupIndex);
           $this->view->assign('groupItem',$groupItem);           
        }


        /**
         *        
         * @param integer $groupIndex
         * @return void
         */
        public function ajaxFileGroupAction(integer $groupIndex) {            
           $this->view->assign('groupIndex',$groupIndex);
           $this->view->assign('displayName','Sekundärdatei');           
        }
                        
}