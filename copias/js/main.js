const URL_API = "https://script.google.com/a/macros/laascension.edu.pe/s/AKfycbz8-HveRpEt3xxOHkdWrfctSNH1NQIm8LdwD5m7W8evPxH0uvQuYYgjvD0QSom_-paFYA/exec"; // La que copiaste al implementar

document.getElementById('solicitudForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  
  const formData = new FormData(e.target);
  const datos = Object.fromEntries(formData.entries());
  
  const response = await fetch(URL_API, {
    method: "POST",
    mode: "no-cors", // Para evitar errores de CORS
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(datos)
  });
  
  alert("¡Solicitud enviada correctamente!");
});