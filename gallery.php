#Пример компонента вывода галереи

#Префикс списка объектов:
<?php echo $f_AdminCommon; ?>
<div class="article-carousel">
  
#Объект в списке:
<?php echo $f_AdminButtons; ?>
<?php echo $f_album_img?>

#Суффикс списка объектов:
<button class="carousel-prev" aria-label="Предыдущее изображение">❮</button>
  <button class="carousel-next" aria-label="Следующее изображение">❯</button>
  <div class="carousel-dots">

  </div>
</div>

#В системных настройках** компонента прописать вывод поля «Множественная загрузка файлов»:

<?php
$f_album_img_tpl = array(
    'prefix' => "<div class='carousel-container'>",
    'record' => "<div class='carousel-slide active'><img src='%Path%' /><div class='carousel-caption'><?=$f_album_title?></div></div>",
    'divider' => " ",
    'suffix' => "</div>",
    'i' => 1
);
?>
