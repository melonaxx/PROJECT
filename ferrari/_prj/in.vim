set nowrap
set encoding=utf-8
set fileencoding=utf-8
set background=dark
set nu

noremap <F2> <Esc>:! $HOME/devspace/ferrari/test/unittest.sh <CR>
set errorformat=%m\ in\ %f\ on\ line\ %l 
call Probe_ide_init("$HOME/devspace/ferrari")
au! BufRead,BufNewFile *.html setfiletype php
