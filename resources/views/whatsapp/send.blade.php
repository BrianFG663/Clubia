<x-filament::page>
    <form id="whatsappForm">
        <x-filament::card>
                <x-filament::input label="Nombre" name="name" value="Lucila" />
                <x-filament::input label="Monto" name="amount" value="2500" />
                <x-filament::input label="Link de pago" name="link" value="https://linkdepago.com" />
            <x-filament::button type="submit">Enviar mensaje</x-filament::button>
        </x-filament::card>
    </form>

    <div id="resultado" class="mt-4 text-sm text-gray-700"></div>

    <script>
        document.getElementById('whatsappForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const name = document.querySelector('[name="name"]').value;
            const amount = document.querySelector('[name="amount"]').value;
            const link = document.querySelector('[name="link"]').value;

            fetch("{{ route('whatsapp.send') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ name, amount, link })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('resultado').innerText = JSON.stringify(data, null, 2);
            })
            .catch(error => {
                document.getElementById('resultado').innerText = 'Error: ' + error;
            });
        });
    </script>
</x-filament::page>
