<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" xmlns:media="http://search.yahoo.com/mrss/" xmlns:atom="http://www.w3.org/2005/Atom" xml:lang='en-GB'>
    <channel>
        <title>Transatlantic Poetry</title>
        <description>Transatlantic Poetry is global poetry movement bringing some of the most exciting poets from the US, UK, Europe and beyond together for live online readings and conversations. With the help of notable partners, we are transforming the way people experience poetry in the twenty-first century.</description>
        <itunes:author>Transatlantic Poetry</itunes:author>
        <link>http://www.transatlanticpoetry.com/YourPodcastHomepage/</link>
        <atom:link href="http://www.transatlanticpoetry.com/readings/rss/" rel="self" type="application/rss+xml" />
        <itunes:image href="http://www.transatlanticpoetry.com/files/2015/06/atlantic-square.png" />
        <itunes:owner>
            <itunes:name>Robert Peake</itunes:name>
            <itunes:email>info@transatlanticpoetry.com</itunes:email>
        </itunes:owner>
        <itunes:category text="Arts">
              <itunes:category text="Literature" />
        </itunes:category>
        <itunes:explicit>yes</itunes:explicit>
        <pubDate><?php echo date('r'); ?></pubDate>
        <language>en-GB</language>
        <copyright><?php echo date('Y'); ?> Transatlantic Poetry</copyright>
        <?php foreach($readings as $reading): ?>
            <?php if( strtotime($reading->date.' '.$reading->time) < time() ): ?>
                <item>
                    <title><?php echo $reading->title; ?></title>
                    <description><?php echo htmlspecialchars(trim($reading->description)); ?></description>
                    <itunes:author><?php echo $reading->partner->name; ?></itunes:author>
                    <pubDate><?php echo date('r', strtotime($reading->date.' '.$reading->time)); ?></pubDate>
                    <link><?php echo Reading::get_guid( $reading ); ?></link>
                    <guid isPermaLink="true"><?php echo Reading::get_guid( $reading ); ?></guid>
                </item>
            <?php endif; ?>
        <?php endforeach; ?>
    </channel>
</rss>
