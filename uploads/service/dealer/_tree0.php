<?php
$dom = '../';
require($dom . 'auth.php');
include('_connect.php');

/* построение дерева категорий
	по умолчанию без элементов - берутся все категории и строится дерево от заданного ID
	это нужно чтобы собрать все ID подкатегорий
	по ним запрашиваем их элементы
	и строим дерево еще раз - уже с элементами $WithItems = true
	*/
function SectionsTree( $ID=0, $WithItems=false ){
  global $SectionsForItems, $Items, $Sections;
  if ( $WithItems ){
    $Sections1 =& $SectionsForItems;
  } else {
    $Sections1 =& $Sections;
  }
  $Tree = array();
  if ( isset($Sections1[$ID]) ){ //если в дереве еще нет самой категории
    $Tree[$ID] = $Sections1[$ID];
    $Sections1['IDs'][] = $ID;
    unset( $Sections1[$ID] );
    if ( $WithItems ) {
      $Tree[$ID]['Items'] = array();
      if ( isset($Items[$ID]) )
        foreach( $Items[$ID] as $Item ){
            $Tree[$ID]['Items'][] = $Item; //добавляем в категорию доступные item-ы
        }
      if ( !count($Tree[$ID]['Items']) )
        $Tree[$ID]['Items'] = '';
    }
    $Tree[$ID]['Sections'] = SectionsTree($ID, $WithItems);
  } else { //дети данной категории
    foreach( $Sections1 as $k => $Section ){
      var_dump($Section);
      if ( isset($Section['ParentSection']) && ($Section['ParentSection']==$ID) ) {
        $Tree[$k] = $Section;
        $Sections1['IDs'][] = $k;
        unset( $Sections1[$k] );
        if ( $WithItems ) {
          $Tree[$k]['Items'] = array();
          if ( isset($Items[$k]) )
            foreach( $Items[$k] as $Item ){
                $Tree[$k]['Items'][] = $Item; //добавляем в категорию доступные item-ы
            }
          if ( !count($Tree[$k]['Items']) )
            $Tree[$k]['Items'] = '';
        }
        $Tree[$k]['Sections'] = SectionsTree($k, $WithItems);
      }
    }
  }
  if ( !count($Tree) ) $Tree = '';
  return $Tree;
}

function GetItemsBySections( $bPublished = False, $WhereItem = '', $ID = 0 ){

  global $pdo, $SectionsForItems, $Items, $Sections;

  $ID = (int)$ID;
  //if ( !is_array($WhereItem) ) $WhereItem = array();
  if ($bPublished) {
    $WhereItem = 'and Publish = 1';
  }

  $stm = $pdo->prepare('select * from DownloadSection order by Priority desc, DownloadSectionID desc');
  $stm->execute();
  $Sections = $stm->fetchAll();

  $Sections['IDs'] = array(); //тут будут id нужных категорий для запроса item-ов

  foreach ( $Sections as $Section ){
    $Sections[$Section['DownloadSectionID']] = $Section;
  }
  $SectionsForItems = $Sections; //все секции еще пригодятся для разбора item-ов

  $TmpTree = SectionsTree( $ID ); //создаем дерево от исходной категории


  foreach ( $SectionsForItems as $k => $Section ){
    if ( !in_array($k, $Sections['IDs']) ){
      unset( $SectionsForItems[$k] ); //удаляем категории, не использованные в дереве $this->SecTree
    }
  }

  $stm = $pdo->prepare('select * from DownloadItem where DownloadSectionID in(' . implode(',', $Sections['IDs']) . ') ' . $WhereItem . ' order by Priority desc, DownloadSectionID desc');
  $stm->execute();
  $Items0 = $stm->fetchAll();

  $Items = array();
  foreach ( $Items0 as $Item ){ //распределим сразу по категориям, в рекурсии не придется обходить весь массив
    if ( !isset( $Items[$Item['DownloadSectionID']] ) )
      $Items[$Item['DownloadSectionID']] = array();
    $Items[$Item['DownloadSectionID']][] = $Item;
  }


  $ItemsTree = SectionsTree( $ID, true ); //создаем дерево c item-ами

  return $ItemsTree;

}

function ViewByCat ( $Section='' ) {
  $Result = '';
  $Sections = '';
  $Items = '';

  if ( is_array($Section['Sections']) ) {
    foreach ($Section['Sections'] as $SubSection) {
      $Sub = ViewByCat($SubSection);
      if ( !empty($Sub) ) $Sections .= $Sub;
    }
  }

  if ( isset($Section['Items']) && is_array($Section['Items']) ) {
    foreach ($Section['Items'] as $Item) {
      $Ite = Wrap(Anchor($Item['Name'], 'download/viewitem/' . $Item['DownloadItemID'], 'Name'), 'div', array('class'=>'ItemName'));
      if ( !empty($Item['Info']) ) $Ite .= Wrap($Item['Info'], 'div', array('class'=>'Inf'));
      if ( !empty($Item['Body']) ) $Ite .=  Anchor('Открыть', 'download/viewitem/' . $Item['DownloadItemID']) . ' ';
      if ( !empty($Item['Url']) ) $Ite .=  Anchor('Скачать', 'download/getitem/' . $Item['DownloadItemID']) . ' ';
      $Ite = Wrap($Ite, 'div', array('class'=>'Item'));
      $class = 'Item';
      if ( !empty($Item['Image']) || !empty($Item['ImageBig']) ) {
        if ( empty($Item['Image']) && !empty($Item['ImageBig']) )
          $Item['Image'] = $Item['ImageBig'];
        $link = 'download/viewitem/' . $Item['DownloadItemID'];
        $classLink = '';
        $arrayLink = array();

        $Ite = Anchor(Wrap(Img($Item['Image']), 'div', array('class'=>'Icon')), $link, $classLink, $arrayLink) . $Ite;
        $class .= ' wIcon';
      }
      $Items .= Wrap($Ite, 'li', array('class'=>$class));
    }
    $Items = Wrap($Items, 'ul', array('class'=>'Items'));
  }

  if ( !empty($Items) || !empty($Sections) ){
    $Result .= Anchor($Section['Name'], 'download/category/' . $Section['DownloadSectionID']);
    $Result = Wrap($Result, 'li', array('class'=>'Category', 'data-id'=>$Section['DownloadSectionID']));
    $Sub = '';
    if ( !empty($Section['Comment']) ) $Sub .= Wrap($Section['Comment'], 'div', array('class'=>'Comment'));
    if ( !empty($Sections) ) $Sections = Wrap($Sections, 'ul');
    $Sub .= $Sections . $Items;
    $Result .= Wrap($Sub, 'li', array('class'=>'Sub'));
  }

  return $Result;
}

function Wrap($text, $tag, $class=''){
  return '<' . $tag . '>' . $text . '</' . $tag . '>';
}
function Anchor($text, $url){
  return '<a href="' . $url . '">' . $text . '</a>';
}
function Img($url){
  return '<img src="' . $url . '">';
}


$ItemsBySections = GetItemsBySections(false, '', 302);

//var_dump($ItemsBySections);

foreach($ItemsBySections as $Section){
  echo ViewByCat($Section);

}

