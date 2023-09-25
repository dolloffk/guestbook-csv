# PHP Guestbook

This is a flat-file PHP guestbook that uses a CSV file to hold all the entries. It has an admin panel where you can optionally approve new comments and reply to them. There are quite a few anti-spam measures built in, but this is designed for my use case, which is a smaller website, and as such may not be as effective for websites that get a lot of traffic (and, by extension, a lot of bots). 

It's not the slickest code, but it does what it needs to do!

## Setup

- Edit `hasher.php` with your password, upload it to your website, and open it. Copy whatever it gives you, then delete the file from your server. Paste that string to `prefs.php`, taking care to escape the dollar signs ($) and remove any spaces. 
- Finish editing `prefs.php` with everything else.
- The only files you need to touch after that are in the folder  `templates`
    - `top.php` and `bottom.php` are the top and bottom files for the entire page - they're set up like that so you can include your website's top and bottom files if desired
    - `gb-header.php` and `gb-footer.php` are the header and footer to the guestbook itself and shouldn't contain any structural HTML tags (that's what the top and bottom are for)
    - `entry.php` is the template for displaying entries. The variables you have to work with are `$name`, `$url`, `$date`, `$comment`, and `$reply`.
    - Additionally, there's a file called `pagination-to-copy.css` with CSS to make the built-in pagination work correctly, should you choose to use your own stylesheet. Feel free to edit as much as necessary.
- Upload everything in the folder to your website wherever you want your guestbook.
- CHMOD `entries.txt` and `queue.txt` to 640 to avoid them being accessed by the public.
- Have fun!

## Troubleshooting

- If stuff doesn't look like it's working after you test your first entry, download and inspect the entries.csv (or queue.csv) file. Sometimes the first entry will get stuck to the end of the header line instead of being put onto its own line. Just hit enter, save, and upload again, and it'll be good to go for all subsequent entries. This *shouldn't* happpen, but you never know...
- Please let me know about any other wonk so I can fix it.