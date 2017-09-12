<?php

namespace Drupal\r3dproduct\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class ProductForm.
 *
 * @package Drupal\r3dproduct\Form
 */
class ProductForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'product_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $conn = Database::getConnection();
     $record = array();
    if (isset($_GET['num'])) {
        $query = $conn->select('r3dproduct', 'm')
            ->condition('id', $_GET['num'])
            ->fields('m');
        $record = $query->execute()->fetchAssoc();
    }

    $form['name'] = array(
        '#type' => 'textfield',
        '#title' => t('Product Name:'),
        '#required' => TRUE,
      );
  
    $form['description'] = array(
        '#type' => 'textfield',
        '#title' => t('Product description:'),
        '#required' => TRUE,
    );

    $form['price'] = array (
        '#type' => 'number',
        '#title' => t('Product price'),
        '#required' => TRUE,
    );
  
    $form['submit'] = [
        '#type' => 'submit',
        '#value' => 'save',
    ];

    return $form;
    }

  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {
        if (!intval($form_state->getValue('price'))) {
            $form_state->setErrorByName('price', $this->t('price needs to be a number'));
        }

        parent::validateForm($form, $form_state);
    }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

        $field=$form_state->getValues();
        $name=$field['name'];
        $description=$field['description'];
        $price=$field['price'];

        $field  = array(
            'name' =>  $name,
            'description' =>  $description,
            'price' =>  $price
        );

        $query = \Drupal::database();
        $query ->insert('r3dproduct')
            ->fields($field)
            ->execute();
        drupal_set_message("succesfully saved");

        $this->sendEmail($field);

        $form_state->setRedirect('r3dproduct.display_table');
    }

    private function sendEmail($field){

        $mailManager = \Drupal::service('plugin.manager.mail');
        
        $module = 'r3dproduct';
        $key = 'node_insert';
        $to = \Drupal::currentUser()->getEmail();
        $params['message'] = $field['description'];
        $params['node_title'] = 'BLA BLA BLA';
        $langcode = \Drupal::currentUser()->getPreferredLangcode();
    
        $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, true);
        if ($result['result'] !== true) {
            $message = t('There was a problem sending your email notification to @email.', array('@email' => $to));
            drupal_set_message($message, 'error');
            \Drupal::logger('r3dproduct')->error($message);
            return;
        }
    
        $message = t('An email notification has been sent to @email', array('@email' => $to));
        drupal_set_message($message);
        \Drupal::logger('r3dproduct')->notice($message);
        
    }
}