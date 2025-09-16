   function getDateTime() {
    const agora = new Date();

  const options = {
    timeZone: 'America/Sao_Paulo',
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    hour12: false
  };

  const partes = new Intl.DateTimeFormat('pt-BR', options).formatToParts(agora);
  const mapa = Object.fromEntries(partes.map(p => [p.type, p.value]));

  return `${mapa.year}-${mapa.month}-${mapa.day} ${mapa.hour}:${mapa.minute}:${mapa.second}`;
}


console.log(getDateTime());