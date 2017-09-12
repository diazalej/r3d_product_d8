<?php

namespace Drupal\r3dproduct\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

/**
 * Class DisplayTableController.
 *
 * @package Drupal\r3dproduct\Controller
 */
class ProductController extends ControllerBase {


  public function getContent() {
    $build = [
      'description' => [
        '#theme' => 'r3dproduct_description',
        '#description' => 'foo',
        '#attributes' => [],
      ],
    ];
    return $build;
  }

  /**
   * Display.
   *
   * @return string
   *   Return Hello string.
   */
  public function display() {
    //create table header
    $header_table = array(
      'id'=>    t('ProdNo'),
      'name' => t('Name'),
      'description' => t('Description'),
      'price' => t('Price'),
    );

    //select records from table
    $query = \Drupal::database()->select('r3dproduct', 'm');
    $query->fields('m', ['id','name','description','price']);
    $results = $query->execute()->fetchAll();
    $rows=array();
    foreach($results as $data){
      //print the data from table
      $rows[] = array(
        'id' =>$data->id,
        'name' => $data->name,
        'description' => $data->description,
        'price' => $data->price,
      );
    }
    //display data in site
    $form['table'] = [
      '#type' => 'table',
      '#header' => $header_table,
      '#rows' => $rows,
      '#empty' => t('No products found'),
    ];
    
    $new = \Drupal::l('New Product', Url::fromRoute('r3dproduct.product_form'));
    
    $form['submit'] = array
    (
      '#type' => 'markup',
      '#markup' => $new,
    );

    return $form;
  }
}