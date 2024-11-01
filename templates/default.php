<ul class="timeline">
    <?php
    $i = 1;
    foreach($entries as $entry)
    {
        $link = \wl_socialstream\EntryHelper::GenerateLink($entry['Type'],$entry['SocialID'],$entry['Title']);
    ?>
    <li class="<?php echo $entry['Type']?><?php echo (($i % 2) == 0? ' timeline-inverted':'')?>">
        <div class="timeline-badge">
            <i class="icon"></i>
        </div>
        <div class="timeline-panel">
            <div class="timeline-heading">
                
                <?php if(!empty($link)){?>
                <h4 class="timeline-title">
                    <?php echo $link?>
                </h4>
                <?php }?>
                <p class="timeline-date">
                    <small class="text-muted"><i class="fa fa-clock-o"></i> <?php echo \wl_socialstream\EntryHelper::GenerateLink($entry['Type'],$entry['SocialID'],$entry['DateString'])?></small>
                </p>
            </div>
            <div class="timeline-body">
                <?php echo $entry['Content']?>
            </div>
        </div>
    </li>
    <?php
        $i++;
    }
    ?>   
</ul>
