class Reszecske
{
    constructor(x, y)
    {
        this.element = document.createElement('div');
        this.element.style.position = 'fixed';
        this.element.style.left = x + 'px';
        this.element.style.top = y + 'px';
        this.element.style.pointerEvents = 'none';
        this.element.style.zIndex = '9999';
        this.element.style.transition = 'transform 0.6s ease-out, opacity 0.6s ease-out';


        document.body.appendChild(this.element);
    }


    mozog(celX, celY, forgas)
    {
        this.element.style.transform = `translate(${celX}px, ${celY}px) rotate(${forgas}deg)`;
        this.element.style.opacity = '0';


        setTimeout(() =>
        {
            this.element.remove();
        }, 600);
    }
}


class PizzaSzelet extends Reszecske
{
    constructor(x, y)
    {
        super(x, y);
        this.element.innerHTML = '🍕';
        this.element.style.fontSize = (15 + Math.random() * 20) + 'px';


        const szog = Math.random() * Math.PI * 2;
        const tavolsag = 50 + Math.random() * 100;
        this.celX = Math.cos(szog) * tavolsag;
        this.celY = Math.sin(szog) * tavolsag;
        this.forgas = Math.random() * 360;
    }


    robban()
    {
        super.mozog(this.celX, this.celY, this.forgas);
    }
}


document.addEventListener('click', function(e)
{

    if (e.target.closest('button'))
    {
        const gomb = e.target.closest('button');
        const doboz = gomb.getBoundingClientRect();


        const startX = doboz.left + (doboz.width / 2) - 10;
        const startY = doboz.top + (doboz.height / 2) - 10;


        for(let i = 0; i < 8; i++)
        {
            const pizza = new PizzaSzelet(startX, startY);


            requestAnimationFrame(() =>
            {
                requestAnimationFrame(() =>
                {
                    pizza.robban();
                });
            });
        }
    }
});