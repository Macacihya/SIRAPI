const fs = require('fs');
const files = fs.readdirSync('resources/views/admin');
for (const file of files) {
  if (file.endsWith('.blade.php')) {
    const code = fs.readFileSync('resources/views/admin/' + file, 'utf8');
    if (code.includes('')) {
      console.log(file + ' CONTAINS MISSING/BAD CHARS');
    }
    const match = code.match(/x-data=\"\{([\s\S]*?)\}\"/);
    if(match) {
        try {
            new Function('return {' + match[1] + '}');
        } catch(e) {
            console.log(file, 'ERROR IN x-data:', e.message);
        }
    }
  }
}
