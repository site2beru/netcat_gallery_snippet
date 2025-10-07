# netcat_gallery_snippet
Сниппет для вывода галереи в произвольном месте текста статьи (в полном выводе объекта) в CMS Netcat

В полном выводе объекта (поле «Отображение объекта:» в вашем компоненте с длинным текстом, размещаемом в поле типа «Текстовый блок» с визуальным редактором)
вставить сниппет вида {{gallery_X}} где Х — ID объекта в разделе с галереями.

Предварительно создать компонент для галереи, например, такой:
 
_Префикс списка объектов:_
<?php echo $f_AdminCommon; ?>
<div class="article-carousel">
  
_Объект в списке:_
<?php echo $f_AdminButtons; ?>
<?php echo $f_album_img?>

_Суффикс списка объектов:_
<button class="carousel-prev" aria-label="Предыдущее изображение">❮</button>
  <button class="carousel-next" aria-label="Следующее изображение">❯</button>
  <div class="carousel-dots">

  </div>
</div>

В системных настройках компонента прописать вывод поля «Множественная загрузка файлов»:

<?php
$f_album_img_tpl = array(
    'prefix' => "<div class='carousel-container'>",
    'record' => "<div class='carousel-slide active'><img src='%Path%' /><div class='carousel-caption'><?=$f_album_title?></div></div>",
    'divider' => " ",
    'suffix' => "</div>",
    'i' => 1
);
?>

Создать раздел с компонентом галереи. ID объекта в нем будет равен X в сниппете {{gallery_X}}
