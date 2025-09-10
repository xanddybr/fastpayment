function getDateTime() {
    const now = new Date();
    return now.toISOString().slice(0,19).replace("T"," ");
}

console.log(getDateTime())